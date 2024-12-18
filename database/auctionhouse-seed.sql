DROP SCHEMA IF EXISTS lbaw2451 CASCADE;
CREATE SCHEMA lbaw2451;
SET search_path TO lbaw2451;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS auction CASCADE;
DROP TABLE IF EXISTS bid CASCADE;
DROP TABLE IF EXISTS rating CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;

DROP TYPE IF EXISTS auction_status;
DROP TYPE IF EXISTS report_status;
DROP TYPE IF EXISTS notif_type;

CREATE TYPE auction_status AS ENUM ('active', 'ended', 'canceled');
CREATE TYPE report_status AS ENUM ('not_processed', 'discarded', 'processed');
CREATE TYPE notif_type AS ENUM ('new_bid', 'bid_withdrawn', 'auction_canceled', 'auction_edited', 'auction_ended', 'auction_followed', 'global_notification');
CREATE TYPE user_status AS ENUM ('active', 'blocked');

CREATE TABLE users (
                       id SERIAL PRIMARY KEY,
                       username TEXT NOT NULL,
                       email TEXT NOT NULL UNIQUE,
                       password TEXT NOT NULL,
                       created_at TIMESTAMP NOT NULL,
                       updated_at TIMESTAMP,
                       profile_picture TEXT,
                       birth_date DATE,
                       address TEXT,
                       is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
                       is_admin BOOLEAN NOT NULL DEFAULT FALSE,
                       remember_token TEXT,
                       balance NUMERIC CHECK (balance >= 0) DEFAULT 0,
                       status user_status DEFAULT 'active'
);

CREATE TABLE category (
                          id SERIAL PRIMARY KEY,
                          name TEXT NOT NULL UNIQUE
);

CREATE TABLE auction (
                         id SERIAL PRIMARY KEY,
                         title TEXT NOT NULL,
                         description TEXT,
                         start_date TIMESTAMP NOT NULL,
                         end_date TIMESTAMP NOT NULL CHECK (end_date >= start_date + INTERVAL '1 day'),
                        status auction_status DEFAULT 'active',
                        minimum_bid NUMERIC CHECK (minimum_bid >= 0) DEFAULT 0,
                        current_bid NUMERIC CHECK (current_bid >= minimum_bid),
                        category_id INTEGER REFERENCES category(id),
                        creator_id INTEGER REFERENCES users(id),
                        buyer_id INTEGER REFERENCES users(id),
                        picture TEXT,
                        created_at TIMESTAMP NOT NULL,
                        updated_at TIMESTAMP
);

CREATE TABLE bid (
                     id SERIAL PRIMARY KEY,
                     amount NUMERIC NOT NULL,
                     auction_id INTEGER REFERENCES auction(id),
                     user_id INTEGER REFERENCES users(id),
                     created_at TIMESTAMP NOT NULL,
                     updated_at TIMESTAMP
);

CREATE TABLE rating (
                        id SERIAL PRIMARY KEY,
                        score INTEGER NOT NULL CHECK (score >= 0 AND score <= 5),
                        comment TEXT,
                        auction_id INTEGER REFERENCES auction(id),
                        rater_id INTEGER REFERENCES users(id),
                        receiver_id INTEGER REFERENCES users(id),
                        created_at TIMESTAMP NOT NULL,
                        updated_at TIMESTAMP
);

CREATE TABLE comment (
                         id SERIAL PRIMARY KEY,
                         text TEXT NOT NULL,
                         auction_id INTEGER REFERENCES auction(id),
                         user_id INTEGER REFERENCES users(id),
                         created_at TIMESTAMP NOT NULL,
                         updated_at TIMESTAMP
);

CREATE TABLE report (
                        id SERIAL PRIMARY KEY,
                        reason TEXT NOT NULL,
                        status report_status DEFAULT 'not_processed',
                        auction_id INTEGER REFERENCES auction(id),
                        user_id INTEGER REFERENCES users(id),
                        created_at TIMESTAMP NOT NULL,
                        updated_at TIMESTAMP
);

CREATE TABLE notifications (
                            id UUID PRIMARY KEY,
                            receiver_id INTEGER REFERENCES users(id),
                            type notif_type NOT NULL,
                            content TEXT NOT NULL,
                            link TEXT,
                            created_at TIMESTAMP NOT NULL,
                            hidden BOOLEAN DEFAULT FALSE
);

CREATE TABLE transactions (
                              id SERIAL PRIMARY KEY,
                              amount NUMERIC NOT NULL,
                              auction_id INTEGER NOT NULL REFERENCES auction(id),
                              created_at TIMESTAMP NOT NULL,
                              updated_at TIMESTAMP
);

CREATE TABLE following (
                              id SERIAL PRIMARY KEY,
                              user_id INTEGER NOT NULL,
                              auction_id INTEGER NOT NULL,
                              created_at TIMESTAMP NOT NULL,
                              updated_at TIMESTAMP,
                              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                              FOREIGN KEY (auction_id) REFERENCES auction(id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL,
    token TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP
);

CREATE INDEX IDX01 ON auction USING BTREE(creator_id);
CREATE INDEX IDX02 ON bid USING BTREE(auction_id);
CREATE INDEX IDX03 ON transactions USING BTREE(auction_id);

-- IDX11
ALTER TABLE auction
    ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION auction_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND (NEW.title <> OLD.title OR NEW.description <> OLD.description)) THEN
     NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.title), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
     );
END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER auction_search_update
    BEFORE INSERT OR UPDATE ON auction
                         FOR EACH ROW
                         EXECUTE PROCEDURE auction_search_update();

CREATE INDEX auction_search_idx ON auction USING GIN (tsvectors);

--TRIGGER01
CREATE OR REPLACE FUNCTION check_bids_before_cancellation()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM bid WHERE auction_id = NEW.id) > 0 THEN
        RAISE EXCEPTION 'Auction with id % cannot be canceled because there are existing bids.', NEW.id;
END IF;

RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_auction_cancellation_with_bids
    BEFORE UPDATE ON auction
    FOR EACH ROW
    WHEN (NEW.status = 'canceled')
    EXECUTE FUNCTION check_bids_before_cancellation();

--TRIGGER02
CREATE OR REPLACE FUNCTION prevent_duplicate_highest_bid()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT user_id
        FROM bid
        WHERE auction_id = NEW.auction_id
        ORDER BY amount DESC, created_at DESC
            LIMIT 1) = NEW.user_id THEN
        RAISE EXCEPTION 'User % already has the highest bid on auction %.', NEW.user_id, NEW.auction_id;
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_user_duplicate_highest_bid
    BEFORE INSERT ON bid
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_highest_bid();

--TRIGGER03
CREATE OR REPLACE FUNCTION extend_auction_if_bid_late()
RETURNS TRIGGER AS $$
BEGIN

    IF (NEW.created_at >= (SELECT end_date - INTERVAL '15 minutes'
                     FROM auction
                     WHERE id = NEW.auction_id)) THEN
UPDATE auction
SET end_date = end_date + INTERVAL '30 minutes'
WHERE id = NEW.auction_id;
END IF;


RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER extend_auction_on_late_bid
    AFTER INSERT ON bid
    FOR EACH ROW
    EXECUTE FUNCTION extend_auction_if_bid_late();

--TRIGGER04
CREATE OR REPLACE FUNCTION prevent_self_review()
RETURNS TRIGGER AS $$
BEGIN

    IF (SELECT creator_id FROM auction WHERE id = NEW.auction_id) = NEW.rater_id THEN
        RAISE EXCEPTION 'User cannot review their own auction (ID: %).', NEW.auction_id;
END IF;
    IF NEW.receiver_id = NEW.rater_id THEN
        RAISE EXCEPTION 'User cannot review their own account (ID: %).', NEW.receiver_id;
END IF;


RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_user_self_review
    BEFORE INSERT ON rating
    FOR EACH ROW
    EXECUTE FUNCTION prevent_self_review();

--TRIGGER05
CREATE OR REPLACE FUNCTION anonymize_user_account()
RETURNS TRIGGER AS $$
BEGIN

UPDATE users
SET
    username = 'deleted_user_' || NEW.id,
    email = 'deleted_' || NEW.id || '@example.com',
    password = NULL,  -- Clear the password for security
    profile_picture = NULL,
    address = NULL,
    birth_date = NULL
WHERE id = NEW.id;


UPDATE users
SET is_deleted = TRUE
WHERE id = NEW.id;


RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_user_before_delete
    BEFORE DELETE ON users
    FOR EACH ROW
    EXECUTE FUNCTION anonymize_user_account();

--TRIGGER06
CREATE OR REPLACE FUNCTION check_auction_dates()
RETURNS TRIGGER AS $$
BEGIN

    IF NEW.end_date < NEW.start_date + INTERVAL '1 day' THEN
        RAISE EXCEPTION 'End date must be at least one day greater than start date for auction ID: %', NEW.id;
END IF;


RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER enforce_auction_date_constraints
    BEFORE INSERT OR UPDATE ON auction
                         FOR EACH ROW
                         EXECUTE FUNCTION check_auction_dates();

--TRIGGER07
CREATE OR REPLACE FUNCTION prevent_admin_auction_creation()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE id = NEW.creator_id AND is_admin = TRUE) THEN
        RAISE EXCEPTION 'Administrators are not allowed to create auctions.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_admin_auction_creation_trigger
    BEFORE INSERT ON auction
    FOR EACH ROW
    EXECUTE FUNCTION prevent_admin_auction_creation();

--TRIGGER08
CREATE OR REPLACE FUNCTION prevent_admin_bid_placement()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if the user placing the bid (user_id) is an admin
    IF EXISTS (SELECT 1 FROM users WHERE id = NEW.user_id AND is_admin = TRUE) THEN
        RAISE EXCEPTION 'Administrators are not allowed to place bids.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_admin_bid_placement_trigger
    BEFORE INSERT ON bid
    FOR EACH ROW
    EXECUTE FUNCTION prevent_admin_bid_placement();

--TRIGGER09
CREATE OR REPLACE FUNCTION prevent_auction_cancellation_with_bids()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM bid WHERE auction_id = NEW.id) > 0 THEN
        RAISE EXCEPTION 'Cannot cancel auction with existing bids.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_auction_cancellation_trigger
    BEFORE UPDATE ON auction
    FOR EACH ROW
    WHEN (NEW.status = 'canceled')
    EXECUTE FUNCTION prevent_auction_cancellation_with_bids();

--TRIGGER10
CREATE OR REPLACE FUNCTION prevent_duplicate_highest_bid()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM bid
        WHERE auction_id = NEW.auction_id
          AND user_id = NEW.user_id
          AND amount = (SELECT MAX(amount) FROM bid WHERE auction_id = NEW.auction_id)
    ) THEN
        RAISE EXCEPTION 'You cannot place a bid if you already have the highest bid.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_highest_bid_trigger
    BEFORE INSERT ON bid
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_highest_bid();

--TRIGGER11
CREATE OR REPLACE FUNCTION extend_auction_deadline()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT end_date FROM auction WHERE id = NEW.auction_id) - NEW.created_at <= INTERVAL '15 minutes' THEN
UPDATE auction
SET end_date = end_date + INTERVAL '30 minutes'
WHERE id = NEW.auction_id;
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER extend_auction_deadline_trigger
    AFTER INSERT ON bid
    FOR EACH ROW
    EXECUTE FUNCTION extend_auction_deadline();

--TRIGGER12
CREATE OR REPLACE FUNCTION prevent_self_review()
RETURNS TRIGGER AS $$
BEGIN
    -- Ensure the receiver is the creator of the auction
    IF NOT EXISTS (
        SELECT 1
        FROM auction
        WHERE id = NEW.auction_id
          AND creator_id = NEW.receiver_id
    ) THEN
        RAISE EXCEPTION 'The receiver must be the creator of the auction.';
END IF;

    -- Ensure the rater is not the same as the receiver
    IF NEW.rater_id = NEW.receiver_id THEN
        RAISE EXCEPTION 'You cannot review your own account.';
END IF;

RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create a trigger to call the function before inserting a new row into the rating table
CREATE TRIGGER prevent_self_review_trigger
    BEFORE INSERT ON rating
    FOR EACH ROW
    EXECUTE FUNCTION prevent_self_review();

--TRIGGER13
CREATE OR REPLACE FUNCTION anonymize_user_data()
RETURNS TRIGGER AS $$
BEGIN

UPDATE users
SET username = 'deleted_user_' || NEW.id,
    email = 'deleted_user_' || NEW.id || '@example.com',
    password = 'deleted',
    profile_picture = NULL,
    birth_date = NULL,
    address = NULL
WHERE id = OLD.id;

RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_user_trigger
    AFTER DELETE ON users
    FOR EACH ROW
    EXECUTE FUNCTION anonymize_user_data();

--TRIGGER14
CREATE OR REPLACE FUNCTION check_auction_dates()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.end_date <= NEW.start_date + INTERVAL '1 day' THEN
        RAISE EXCEPTION 'The auction end date must be at least one day after the start date.';
END IF;

RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_auction_dates_trigger
    BEFORE INSERT OR UPDATE ON auction
                         FOR EACH ROW
                         EXECUTE FUNCTION check_auction_dates();

-- Reset sequences
ALTER SEQUENCE users_id_seq RESTART WITH 1;
ALTER SEQUENCE category_id_seq RESTART WITH 1;
ALTER SEQUENCE auction_id_seq RESTART WITH 1;
ALTER SEQUENCE bid_id_seq RESTART WITH 1;
ALTER SEQUENCE rating_id_seq RESTART WITH 1;
ALTER SEQUENCE comment_id_seq RESTART WITH 1;
ALTER SEQUENCE report_id_seq RESTART WITH 1;
ALTER SEQUENCE transactions_id_seq RESTART WITH 1;

-- Insert Categories
INSERT INTO category (name) VALUES
                                ('Electronics'),
                                ('Fashion'),
                                ('Home & Garden'),
                                ('Sports'),
                                ('Collectibles'),
                                ('Art'),
                                ('Books'),
                                ('Vehicles'),
                                ('Jewelry'),
                                ('Toys & Games');

-- Insert Regular Users (non-admin)
INSERT INTO users (username, email, password, created_at, birth_date, is_admin) VALUES
                                                                                    ('john_doe', 'john@example.com', 'hashed_password1', NOW() - INTERVAL '6 months', '1990-01-15', FALSE),
                                                                                    ('jane_smith', 'jane@example.com', 'hashed_password2', NOW() - INTERVAL '5 months', '1992-03-20', FALSE),
                                                                                    ('bob_wilson', 'bob@example.com', 'hashed_password3', NOW() - INTERVAL '4 months', '1988-07-10', FALSE),
                                                                                    ('alice_johnson', 'alice@example.com', 'hashed_password4', NOW() - INTERVAL '3 months', '1995-11-30', FALSE),
                                                                                    ('mike_brown', 'mike@example.com', 'hashed_password5', NOW() - INTERVAL '2 months', '1991-09-25', FALSE),
                                                                                    ('sarah_davis', 'sarah@example.com', 'hashed_password6', NOW() - INTERVAL '1 month', '1993-04-05', FALSE),
                                                                                    ('david_miller', 'david@example.com', 'hashed_password7', NOW() - INTERVAL '2 months', '1987-12-15', FALSE),
                                                                                    ('emma_wilson', 'emma@example.com', 'hashed_password8', NOW() - INTERVAL '3 months', '1994-06-20', FALSE),
                                                                                    ('chris_taylor', 'chris@example.com', 'hashed_password9', NOW() - INTERVAL '4 months', '1989-08-08', FALSE),
                                                                                    ('lisa_anderson', 'lisa@example.com', 'hashed_password10', NOW() - INTERVAL '5 months', '1996-02-28', FALSE);

-- Insert Admin Users
INSERT INTO users (username, email, password, created_at, birth_date, is_admin) VALUES
    ('admin_user', 'admin@admin.com', '$2y$10$D0ZvErAnuZcWxbBkABw9busMMoWqwyDU80qi2pzBaa4imHrHIOAlW', NOW() - INTERVAL '1 year', '1985-05-15', TRUE);

-- Insert Auctions
-- Note: Using NOW() + INTERVAL for end dates to ensure they span the next 2 months
INSERT INTO auction (title, description, start_date, end_date, status, minimum_bid, current_bid, category_id, creator_id, created_at) VALUES
-- Electronics Category
('iPhone 13 Pro Max', 'Mint condition, 256GB storage', NOW(), NOW() + INTERVAL '15 days', 'active', 500, 500, 1, 1, NOW()),
('Sony PS5', 'Brand new, sealed in box', NOW(), NOW() + INTERVAL '20 days', 'active', 400, 400, 1, 2, NOW()),
('MacBook Pro M1', '13-inch, 512GB SSD', NOW(), NOW() + INTERVAL '25 days', 'active', 800, 800, 1, 3, NOW()),
('Samsung 4K TV', '55-inch Smart TV', NOW(), NOW() + INTERVAL '30 days', 'active', 300, 300, 1, 4, NOW()),

-- Fashion Category
('Vintage Leather Jacket', 'Genuine leather, size M', NOW(), NOW() + INTERVAL '18 days', 'active', 100, 100, 2, 5, NOW()),
('Designer Handbag', 'Limited edition', NOW(), NOW() + INTERVAL '22 days', 'active', 200, 200, 2, 6, NOW()),
('Luxury Watch', 'Swiss made, automatic', NOW(), NOW() + INTERVAL '27 days', 'active', 1000, 1000, 2, 7, NOW()),

-- Home & Garden
('Antique Dining Table', 'Solid oak, seats 8', NOW(), NOW() + INTERVAL '35 days', 'active', 400, 400, 3, 8, NOW()),
('Garden Tool Set', 'Complete set, barely used', NOW(), NOW() + INTERVAL '40 days', 'active', 50, 50, 3, 9, NOW()),
('Modern Sofa', 'Italian leather, white', NOW(), NOW() + INTERVAL '45 days', 'active', 600, 600, 3, 10, NOW()),

-- Sports
('Mountain Bike', 'Professional grade', NOW(), NOW() + INTERVAL '17 days', 'active', 300, 300, 4, 1, NOW()),
('Tennis Racket Set', 'Professional grade', NOW(), NOW() + INTERVAL '23 days', 'active', 80, 80, 4, 2, NOW()),
('Golf Club Set', 'Complete set with bag', NOW(), NOW() + INTERVAL '28 days', 'active', 400, 400, 4, 3, NOW()),

-- Collectibles
('Rare Comic Book', 'First edition, mint condition', NOW(), NOW() + INTERVAL '33 days', 'active', 1000, 1000, 5, 4, NOW()),
('Vintage Baseball Cards', 'Set of 10 rare cards', NOW(), NOW() + INTERVAL '38 days', 'active', 500, 500, 5, 5, NOW()),
('Ancient Coin Collection', 'Roman Empire era', NOW(), NOW() + INTERVAL '43 days', 'active', 2000, 2000, 5, 6, NOW()),

-- Art
('Original Oil Painting', 'Landscape scene, framed', NOW(), NOW() + INTERVAL '19 days', 'active', 300, 300, 6, 7, NOW()),
('Bronze Sculpture', 'Modern art piece', NOW(), NOW() + INTERVAL '24 days', 'active', 400, 400, 6, 8, NOW()),
('Photography Collection', 'Nature themes, signed', NOW(), NOW() + INTERVAL '29 days', 'active', 200, 200, 6, 9, NOW()),

-- Books
('First Edition Novel', 'Classic literature', NOW(), NOW() + INTERVAL '34 days', 'active', 150, 150, 7, 10, NOW()),
('Rare Cookbook Collection', 'Vintage recipes', NOW(), NOW() + INTERVAL '39 days', 'active', 100, 100, 7, 1, NOW()),
('Academic Textbooks', 'University level, mint condition', NOW(), NOW() + INTERVAL '44 days', 'active', 80, 80, 7, 2, NOW()),

-- Vehicles
('Vintage Motorcycle', 'Restored classic', NOW(), NOW() + INTERVAL '21 days', 'active', 5000, 5000, 8, 3, NOW()),
('Classic Car', 'Fully restored', NOW(), NOW() + INTERVAL '26 days', 'active', 15000, 15000, 8, 4, NOW()),
('Electric Scooter', 'Like new condition', NOW(), NOW() + INTERVAL '31 days', 'active', 200, 200, 8, 5, NOW()),

-- Jewelry
('Diamond Ring', '18K gold, certified', NOW(), NOW() + INTERVAL '36 days', 'active', 2000, 2000, 9, 6, NOW()),
('Pearl Necklace', 'Authentic freshwater pearls', NOW(), NOW() + INTERVAL '41 days', 'active', 300, 300, 9, 7, NOW()),
('Vintage Bracelet', 'Art deco style', NOW(), NOW() + INTERVAL '46 days', 'active', 150, 150, 9, 8, NOW()),

-- Toys & Games
('LEGO Collection', 'Rare sets, sealed', NOW(), NOW() + INTERVAL '32 days', 'active', 200, 200, 10, 9, NOW()),
('Board Game Bundle', 'Strategy games collection', NOW(), NOW() + INTERVAL '37 days', 'active', 100, 100, 10, 10, NOW()),
('Vintage Action Figures', 'Collector''s edition', NOW(), NOW() + INTERVAL '42 days', 'active', 300, 300, 10, 1, NOW());

-- Insert some initial bids
INSERT INTO bid (amount, auction_id, user_id, created_at) VALUES
                                                              (550, 1, 2, NOW() + INTERVAL '1 day'),
                                                              (600, 1, 3, NOW() + INTERVAL '2 days'),
                                                              (450, 2, 4, NOW() + INTERVAL '1 day'),
                                                              (850, 3, 5, NOW() + INTERVAL '1 day'),
                                                              (900, 3, 6, NOW() + INTERVAL '2 days'),
                                                              (350, 4, 7, NOW() + INTERVAL '1 day'),
                                                              (150, 5, 8, NOW() + INTERVAL '1 day'),
                                                              (250, 6, 9, NOW() + INTERVAL '1 day'),
                                                              (1100, 7, 10, NOW() + INTERVAL '1 day'),
                                                              (450, 8, 1, NOW() + INTERVAL '1 day');

-- Insert some comments
INSERT INTO comment (text, auction_id, user_id, created_at) VALUES
                                                                ('Is this still available?', 1, 2, NOW()),
                                                                ('What''s the condition like?', 1, 3, NOW() + INTERVAL '1 hour'),
                                                                ('Great price!', 2, 4, NOW()),
                                                                ('Can you ship internationally?', 3, 5, NOW()),
                                                                ('Interested!', 4, 6, NOW());

-- Insert some reports
INSERT INTO report (reason, status, auction_id, user_id, created_at) VALUES
                                                                         ('Suspicious pricing', 'not_processed', 1, 2, NOW()),
                                                                         ('Incorrect description', 'not_processed', 3, 4, NOW()),
                                                                         ('Potential counterfeit', 'not_processed', 6, 7, NOW());

-- Update current_bid values based on highest bids
UPDATE auction a
SET current_bid = (
    SELECT MAX(b.amount)
    FROM bid b
    WHERE b.auction_id = a.id
)
WHERE EXISTS (
    SELECT 1
    FROM bid b
    WHERE b.auction_id = a.id
);

-- Insert ended auctions
INSERT INTO auction (title, description, start_date, end_date, status, minimum_bid, current_bid, category_id, creator_id, buyer_id, created_at) VALUES
('Old Laptop', 'Used laptop, good condition', NOW() - INTERVAL '30 days', NOW() - INTERVAL '15 days', 'ended', 100, 150, 1, 1, 3, NOW() - INTERVAL '30 days'),
('Vintage Camera', 'Classic film camera', NOW() - INTERVAL '40 days', NOW() - INTERVAL '20 days', 'ended', 200, 250, 2, 9, 4, NOW() - INTERVAL '40 days'),
('Antique Vase', 'Beautiful antique vase', NOW() - INTERVAL '50 days', NOW() - INTERVAL '25 days', 'ended', 300, 350, 3, 1, 5, NOW() - INTERVAL '50 days'),
('Gaming Console', 'Latest model, barely used', NOW() - INTERVAL '60 days', NOW() - INTERVAL '30 days', 'ended', 400, 450, 4, 6, 1, NOW() - INTERVAL '60 days');

-- Insert associated transactions
INSERT INTO transactions (amount, auction_id, created_at) VALUES
(150, (SELECT id FROM auction WHERE title = 'Old Laptop'), NOW() - INTERVAL '15 days'),
(250, (SELECT id FROM auction WHERE title = 'Vintage Camera'), NOW() - INTERVAL '20 days'),
(350, (SELECT id FROM auction WHERE title = 'Antique Vase'), NOW() - INTERVAL '25 days'),
(450, (SELECT id FROM auction WHERE title = 'Gaming Console'), NOW() - INTERVAL '30 days');