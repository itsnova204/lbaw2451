SET search_path TO lbaw2451;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS auction CASCADE;
DROP TABLE IF EXISTS bid CASCADE;
DROP TABLE IF EXISTS rating CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;

DROP TYPE IF EXISTS auction_status;
DROP TYPE IF EXISTS report_status;
DROP TYPE IF EXISTS notif_type;

CREATE TYPE auction_status AS ENUM ('active', 'ended', 'canceled');
CREATE TYPE report_status AS ENUM ('not_processed', 'discarded', 'processed');
CREATE TYPE notif_type AS ENUM ('generic', 'new_bid', 'bid_surpassed', 'auction_end', 'new_comment', 'report');


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
    is_admin BOOLEAN,
    remember_token TEXT
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

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL,
    type notif_type DEFAULT 'generic',
    receiver_id INTEGER REFERENCES users(id),
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP
);

CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    amount NUMERIC NOT NULL,
    auction_id INTEGER NOT NULL REFERENCES auction(id),
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
