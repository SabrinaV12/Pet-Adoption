<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../adminUsers.php");
    exit;
}

$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$role = $_POST['role'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if (empty($id) || empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($phone_number) || empty($role)) {
    die("Error: All fields except password are required.");
}

$sql_check = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param('ssi', $username, $email, $id);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    die("Error: Another user with this username or email already exists.");
}
$stmt_check->close();

if ($id == $_SESSION['user_id'] && $role != 'admin') {
    die("Error: You cannot remove your own admin status.");
}


$sql_parts = [
    "first_name = ?",
    "last_name = ?",
    "username = ?",
    "email = ?",
    "phone_number = ?",
    "role = ?"
];
$params = [
    $first_name,
    $last_name,
    $username,
    $email,
    $phone_number,
    $role
];
$types = 'ssssss';

if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql_parts[] = "hash_password = ?";
    $params[] = $hashed_password;
    $types .= 's';
}

$sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id = ?";
$params[] = $id;
$types .= 'i';

$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: ../adminUsers.php?status=user_updated");
} else {
    header("Location: ../adminUsers.php?status=error&msg=" . urlencode($stmt->error));
}
$stmt->close();
$conn->close();
