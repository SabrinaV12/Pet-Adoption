<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "temp_users";

$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Eroare la conectarea cu baza de date. Incearca mai tarziu.");
}

$sql = "CREATE TABLE IF NOT EXISTS User (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'User' was created successfully or it's already created.<br>";
} else {
    echo "Error when creating table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) NOT NULL,
    selector CHAR(24) NOT NULL UNIQUE,
    hashed_validator CHAR(64) NOT NULL,
    expires DATETIME NOT NULL,
    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'auth_tokens' was created successsfully or it's already created.<br>";
} else {
    echo "Erorr when creating table: " . $conn->error . "<br>";
}
