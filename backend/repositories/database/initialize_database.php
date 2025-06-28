<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pet_adopt';

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($sql)) {
    die("Eroare la crearea bazei de date: " . $conn->error);
}

$conn->select_db($database);

$queries = [

    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone_number VARCHAR(10) NOT NULL,
        hash_password VARCHAR(100) NOT NULL,
        role ENUM('user','admin') NOT NULL,
        description TEXT,
        country VARCHAR(25) NOT NULL,
        county VARCHAR(25) NOT NULL,
        telegram_handle VARCHAR(50) NOT NULL,
        profile_picture VARCHAR(50) NOT NULL,
        banner_picture VARCHAR(50) NOT NULL,
        CHECK (first_name NOT REGEXP '[0-9]'),
        CHECK (last_name NOT REGEXP '[0-9]'),
        CHECK (email LIKE '%@%'),
        CHECK (phone_number REGEXP '^[0-9]{10}$')
    )",

    "CREATE TABLE IF NOT EXISTS auth_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        selector CHAR(24) NOT NULL,
        hashed_validator CHAR(64) NOT NULL,
        expires DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS pets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        gender ENUM('Male', 'Female') NOT NULL,
        breed VARCHAR(50),
        age INT NOT NULL CHECK (age >= 0),
        color VARCHAR(50),
        weight DECIMAL(5,2) CHECK (weight >= 0),
        height DECIMAL(5,2) CHECK (height >= 0),
        animal_type ENUM('Dog', 'Cat', 'Capybara') NOT NULL,
        image_path VARCHAR(255),
        size ENUM('Small', 'Medium', 'Large') NOT NULL,
        user_id INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        vaccinated BOOLEAN DEFAULT FALSE,
        house_trained BOOLEAN DEFAULT FALSE,
        neutered BOOLEAN DEFAULT FALSE,
        microchipped BOOLEAN DEFAULT FALSE,
        good_with_children BOOLEAN DEFAULT FALSE,
        shots_up_to_date BOOLEAN DEFAULT FALSE,

        restrictions TEXT,
        recommended TEXT,
        
        adopted BOOLEAN DEFAULT FALSE,
        adoption_date DATE DEFAULT NULL,
        
        description TEXT,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )",

    "CREATE TABLE IF NOT EXISTS feeding_calendar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pet_id INT,
        feed_date DATE,
        food_type VARCHAR(255),
        FOREIGN KEY (pet_id) REFERENCES pets(id)
    )",

    "CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,

        address_line1 VARCHAR(100) NOT NULL,
        address_line2 VARCHAR(100),
        postcode VARCHAR(20) NOT NULL,
        city VARCHAR(100) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,

        has_garden ENUM('Yes', 'No') NOT NULL,
        living_situation TEXT NOT NULL,
        household_setting TEXT NOT NULL,
        activity_level INT NOT NULL CHECK (activity_level BETWEEN 1 AND 10),

        num_adults INT CHECK (num_adults>=1),
        num_children INT NOT NULL,
        youngest_child_age INT CHECK (youngest_child_age BETWEEN 1 AND 18),
        visiting_children ENUM('Yes', 'No'),
        visiting_children_ages INT CHECK (visiting_children_ages BETWEEN 1 AND 18),

        has_flatmates ENUM('Yes', 'No'),

        has_allergies TEXT NOT NULL,
        has_other_animals ENUM('Yes', 'No') NOT NULL,
        other_animals_info TEXT,
        neutered ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
        vaccinated ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
        experience TEXT,

        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS pet_media (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pet_id INT NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        file_type ENUM('image', 'video', 'audio') NOT NULL,
        FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS vaccinations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pet_id INT,
        age_in_weeks INT,
        vaccine_name VARCHAR(255),
        FOREIGN KEY (pet_id) REFERENCES pets(id)
    )",

    "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message VARCHAR(255) NOT NULL,
        link VARCHAR(255),
        is_read BOOLEAN NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS pet_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        gender ENUM('Male', 'Female') NOT NULL,
        breed VARCHAR(50),
        age INT NOT NULL,
        color VARCHAR(50),
        weight DECIMAL(5,2),
        height DECIMAL(5,2),
        animal_type ENUM('Dog', 'Cat', 'Capybara') NOT NULL,
        image_path VARCHAR(255),
        size ENUM('Small', 'Medium', 'Large') NOT NULL,
        vaccinated TINYINT(1) DEFAULT 0,
        house_trained TINYINT(1) DEFAULT 0,
        neutered TINYINT(1) DEFAULT 0,
        microchipped TINYINT(1) DEFAULT 0,
        good_with_children TINYINT(1) DEFAULT 0,
        shots_up_to_date TINYINT(1) DEFAULT 0,
        restrictions TEXT,
        recommended TEXT,
        adopted TINYINT(1) DEFAULT 0,
        adoption_date DATE,
        description TEXT,
        user_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS feeding_calendar_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        request_id INT,
        feed_date DATE,
        food_type VARCHAR(255),
        FOREIGN KEY (request_id) REFERENCES pet_requests(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS vaccinations_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        request_id INT,
        age_in_weeks INT,
        vaccine_name VARCHAR(255),
        FOREIGN KEY (request_id) REFERENCES pet_requests(id) ON DELETE CASCADE
    )"
];

foreach ($queries as $query) {
    if (!$conn->query($query)) {
        echo "Eroare la executarea comenzii: " . $conn->error . "<br>";
    }
}

echo "Tabelele au fost create cu succes.";

$conn->close();
