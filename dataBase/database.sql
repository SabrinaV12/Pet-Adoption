CREATE DATABASE IF NOT EXISTS pet_adopt;
USE pet_adopt;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(10) NOT NULL,
    hash_password VARCHAR(100) NOT NULL,
    CHECK (first_name NOT REGEXP '[0-9]'),
    CHECK (last_name NOT REGEXP '[0-9]'),
    CHECK (email LIKE '%@%'),
    CHECK (phone_number REGEXP '^[0-9]{10}$')
);

CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    selector CHAR(24) NOT NULL,
    hashed_validator CHAR(64) NOT NULL,
    expires DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


