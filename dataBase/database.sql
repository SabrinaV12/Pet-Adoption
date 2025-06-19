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


INSERT INTO users (first_name, last_name, username, email, phone_number, hash_password)
VALUES 
('Hosa', 'Hosos', 'hosa', 'hosa@hosa.com', '1234567890', 'hosaa'),
('Fosa', 'Fosos', 'fosa', 'fosa@fosa.com', '123456789', 'fosaa');
