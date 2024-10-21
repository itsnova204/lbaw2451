SET search_path TO lbaw2451;

DROP TABLE IF EXISTS account CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS auction CASCADE;
DROP TABLE IF EXISTS bid CASCADE;
DROP TABLE IF EXISTS rating CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;

CREATE TABLE account (
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
    id INTEGER PRIMARY KEY REFERENCES account(id),
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE admin (
    id INTEGER PRIMARY KEY REFERENCES account(id)
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
    status TEXT DEFAULT 'active',
    minimum_bid NUMERIC CHECK (minimum_bid >= 0) DEFAULT 0,
    current_bid NUMERIC CHECK (current_bid >= minimum_bid),
    category_id INTEGER REFERENCES category(id),
    creator_id INTEGER REFERENCES users(id),
    buyer_id INTEGER REFERENCES users(id)
);

CREATE TABLE bid (
    id SERIAL PRIMARY KEY,
    amount NUMERIC NOT NULL,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auction(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE rating (
    id SERIAL PRIMARY KEY,
    score INTEGER NOT NULL CHECK (score >= 0 AND score <= 5),
    comment TEXT,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auction(id),
    rater_id INTEGER REFERENCES users(id),
    receiver_id INTEGER REFERENCES users(id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    auction_id INTEGER REFERENCES auction(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    status TEXT DEFAULT 'not_processed',
    auction_id INTEGER REFERENCES auction(id),
    user_id INTEGER REFERENCES users(id)
);

CREATE TABLE notification (
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
    auction_id INTEGER NOT NULL REFERENCES auction(id)
);
