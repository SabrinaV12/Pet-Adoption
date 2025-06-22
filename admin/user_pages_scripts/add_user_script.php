<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($username) || empty($email) || empty($password) || empty($role)) {
        die("Error: All fields are required.");
    }
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('ss', $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        die("Error: A user with this username or email already exists.");
    }
    $stmt_check->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO users (first_name, last_name, phone_number, username, email, hash_password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    $stmt_insert->bind_param(
        'sssssss',
        $first_name,
        $last_name,
        $phone_number,
        $username,
        $email,
        $hashed_password,
        $role
    );

    if ($stmt_insert->execute()) {
        header("Location: ../adminUsers.php?status=user_added");
        exit;
    } else {
        header("Location: ../adminUsers.php?status=error&msg=" . urlencode($stmt_insert->error));
        exit;
    }
    $stmt_insert->close();
    $conn->close();
} else {
    header("Location: add_user.php");
    exit;
}
