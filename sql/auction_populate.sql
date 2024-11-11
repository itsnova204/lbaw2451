SET search_path TO lbaw2451;

-- Populate accounts table
INSERT INTO users (id, username, email, password, created_at, profile_picture, birth_date, address, is_deleted, is_admin) VALUES
                                                                                                                              (1, 'john_doe', 'john.doe@example.com', 'hashed_password_1', '2024-01-10 08:30:00', 'profile1.jpg', '1990-05-15', '123 Main St, Lisbon', FALSE, FALSE),  -- john_doe
                                                                                                                              (2, 'jane_smith', 'jane.smith@example.com', 'hashed_password_2', '2024-01-11 09:00:00', 'profile2.jpg', '1995-08-20', '456 Elm St, Porto', FALSE, FALSE),  -- jane_smith
                                                                                                                              (3, 'admin_user', 'admin@example.com', 'hashed_password_admin', '2024-01-05 07:45:00', 'admin.jpg', '1988-11-30', '789 Maple St, Coimbra', FALSE, TRUE); -- admin_user (is_admin = TRUE)


-- Populate categories table
INSERT INTO category (name) VALUES
('Electronics'),
('Furniture'),
('Books'),
('Clothing'),
('Toys');

-- Populate auctions table
INSERT INTO auction (title, description, start_date, end_date, status, minimum_bid, current_bid, category_id, creator_id, buyer_id, picture, created_at) VALUES
('Laptop for Sale', 'A gently used laptop in great condition.', '2024-10-01 10:00:00', '2024-10-03 10:00:00', 'active', 100, 150, 1, 1, NULL, '/path', '2024-10-01 10:00:00'),
('Vintage Chair', 'A beautiful vintage chair for your living room.', '2024-10-02 09:00:00', '2024-10-05 09:00:00', 'active', 50, 70, 2, 2, NULL, '/path', '2024-10-02 09:00:00'),
('Programming Book', 'Learn Haskell programming with this great book.', '2024-10-03 11:00:00', '2024-10-10 11:00:00', 'active', 20, 20, 1, 1, NULL, '/path', '2024-10-03 11:00:00');

-- Populate bids table
INSERT INTO bid (amount, created_at, auction_id, user_id) VALUES
(120, '2024-10-01 11:00:00', 1, 2), -- Jane bids on the Laptop
(160, '2024-10-02 10:00:00', 1, 1), -- John bids on the Laptop
(60, '2024-10-03 10:00:00', 2, 2); -- Jane bids on the Vintage Chair

-- Populate ratings table
INSERT INTO rating (score, comment, created_at, auction_id, rater_id, receiver_id) VALUES
(5, 'Excellent transaction!', '2024-10-04 12:00:00', 1, 2, 1), -- Jane rates John after buying the Laptop
(4, 'Nice chair, but it has some scratches.', '2024-10-06 14:00:00', 2, 1, 2); -- John rates Jane after buying the Chair

-- Populate comments table
INSERT INTO comment (text, created_at, auction_id, user_id) VALUES
('Is the laptop still available?', '2024-10-01 12:00:00', 1, 2),
('What is the condition of the chair?', '2024-10-03 09:00:00', 2, 1);

-- Populate reports table
INSERT INTO report (reason, created_at, status, auction_id, user_id) VALUES
('Inappropriate description', '2024-10-04 10:00:00', 'not_processed', 3, 2); -- Jane reports the Programming Book

-- Populate notifications table
INSERT INTO notification (text, created_at, type, receiver_id) VALUES
('You have a new bid on your auction!', '2024-10-02 15:00:00', 'new_bid', 1),
('Your auction has ended!', '2024-10-04 09:00:00', 'auction_end', 1);

-- Populate transactions table
INSERT INTO transactions (amount, created_at, auction_id) VALUES
(150, '2024-10-04 13:00:00', 1), -- Transaction for Laptop sale
(70, '2024-10-06 15:00:00', 2); -- Transaction for Vintage Chair sale
