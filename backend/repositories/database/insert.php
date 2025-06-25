<?php
require_once 'db.php';

$first_name = 'Sabrina';
$last_name = 'Valentina';
$username = 'sabrina';
$email = 'sabrina@yahoo.com';
$phone_number = '0721123456';
$password_plain = 'sabrina';
$role = 'admin';
$description = 'Iubitoare de animale și voluntara la adăpost.';
$country = 'România';
$county = 'Iasi';
$telegram_handle = 'sabrina';
$profile_picture = 'woman.jpg';
$banner_picture = 'cherry.jpg';

$hash_password = password_hash($password_plain, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (
    first_name, last_name, username, email, phone_number, hash_password, role, description,
    country, county, telegram_handle, profile_picture, banner_picture
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sssssssssssss",
    $first_name,
    $last_name,
    $username,
    $email,
    $phone_number,
    $hash_password,
    $role,
    $description,
    $country,
    $county,
    $telegram_handle,
    $profile_picture,
    $banner_picture
);

if ($stmt->execute()) {
    echo "Utilizatorul a fost inserat cu succes!";
} else {
    echo "Eroare la inserare: " . $stmt->error;
}

$stmt->close();
$conn->close();
