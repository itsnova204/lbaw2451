SET search_path TO lbaw2451;

CREATE TABLE accounts (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    registration_date TIMESTAMP NOT NULL,
    profile_picture TEXT,
    birth_date DATE,
    address TEXT
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY REFERENCES accounts(id),
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE admins (
    id INTEGER PRIMARY KEY REFERENCES accounts(id)
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE auctions (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL CHECK (end_date >= start_date + INTERVAL '1 day'),
    status TEXT DEFAULT 'active',
    minimum_bid NUMERIC CHECK (minimum_bid >= 0) DEFAULT 0,
    current_bid NUMERIC CHECK (current_bid >= minimum_bid),
    category_id INTEGER REFERENCES categories(id),
    creator_id INTEGER REFERENCES users(id),
    buyer_id INTEGER REFERENCES users(id)
);

CREATE TABLE bids (
    id SERIAL PRIMARY KEY,
    amount NUMERIC NOT NULL,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auctions(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE ratings (
    id SERIAL PRIMARY KEY,
    score INTEGER NOT NULL CHECK (score >= 0 AND score <= 5),
    comment TEXT,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auctions(id),
    rater_id INTEGER REFERENCES users(id),
    receiver_id INTEGER REFERENCES users(id)
);

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auctions(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE reports (
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    status TEXT DEFAULT 'not_processed',
    auction_id INTEGER REFERENCES auctions(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    type TEXT DEFAULT 'generic',
    receiver_id INTEGER REFERENCES users(id)
);

CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    amount NUMERIC NOT NULL,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER NOT NULL REFERENCES auctions(id)
);
