-- Reset sequences
ALTER SEQUENCE users_id_seq RESTART WITH 1;
ALTER SEQUENCE category_id_seq RESTART WITH 1;
ALTER SEQUENCE auction_id_seq RESTART WITH 1;
ALTER SEQUENCE bid_id_seq RESTART WITH 1;
ALTER SEQUENCE rating_id_seq RESTART WITH 1;
ALTER SEQUENCE comment_id_seq RESTART WITH 1;
ALTER SEQUENCE report_id_seq RESTART WITH 1;
ALTER SEQUENCE notification_id_seq RESTART WITH 1;
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
    ('admin_user', 'admin@admin.com', '2y$10$HokRQTNn7UNd2cmp0AsJkuTwC0TUHN5dBumc0y4Nq6hhbqcGGf1k.', NOW() - INTERVAL '1 year', '1985-05-15', TRUE);

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

-- Insert some notifications
INSERT INTO notification (text, type, receiver_id, created_at) VALUES
                                                                   ('Your auction has received a new bid!', 'new_bid', 1, NOW()),
                                                                   ('Someone commented on your auction', 'new_comment', 1, NOW()),
                                                                   ('Your bid has been surpassed', 'bid_surpassed', 2, NOW()),
                                                                   ('Auction ending soon!', 'auction_end', 3, NOW());

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