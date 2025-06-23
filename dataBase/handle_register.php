<?php
require_once 'db.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$country = $_POST['country'];
$county = $_POST['county'];
$telegram = $_POST['telegram_handle'];
$description = $_POST['description'];

if ($password !== $confirm_password) {
    header("Location: ../register.php?error=password_mismatch");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($existingId);
    $stmt->fetch();

    if ($existingId) {
        header("Location: ../register.php?error=email_exists");
    } else {
        header("Location: ../register.php?error=username_exists");
    }
    exit;
}
$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$profilePictureName = uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);
$profileTarget = "../assets/" . $profilePictureName;
move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profileTarget);

$bannerPictureName = uniqid() . '_' . basename($_FILES["banner_picture"]["name"]);
$bannerTarget = "../assets/" . $bannerPictureName;
move_uploaded_file($_FILES["banner_picture"]["tmp_name"], $bannerTarget);

$insert = $conn->prepare("INSERT INTO users (
    first_name, last_name, username, email, phone_number, hash_password, role,
    country, county, telegram_handle, profile_picture, banner_picture, description
) VALUES (?, ?, ?, ?, ?, ?, 'user', ?, ?, ?, ?, ?, ?)");

$insert->bind_param(
    "ssssssssssss",
    $first_name,
    $last_name,
    $username,
    $email,
    $phone,
    $hashed_password,
    $country,
    $county,
    $telegram,
    $profilePictureName,
    $bannerPictureName,
    $description
);

if ($insert->execute()) {
    header("Location: ../login.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
