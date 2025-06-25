<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: ../adminUsers.php?status=invalid_id');
    exit;
}

$user_id_to_delete = $_GET['id'];

if ($user_id_to_delete == $_SESSION['user_id']) {
    header('Location: ../adminUsers.php?status=error&msg=' . urlencode('You cannot delete your own account.'));
    exit;
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $user_id_to_delete);

if ($stmt->execute()) {
    header('Location: ../adminUsers.php?status=user_deleted');
    exit;
} else {
    header('Location: ../adminUsers.php?status=error&msg=' . urlencode($stmt->error));
    exit;
}

$stmt->close();
$conn->close();
