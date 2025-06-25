<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: ../adminAnimals.php?status=invalid_id');
    exit;
}

$pet_id_to_delete = $_GET['id'];

$sql_select_image = "SELECT image_path FROM pets WHERE id = ?";
$stmt_select = $conn->prepare($sql_select_image);
$stmt_select->bind_param('i', $pet_id_to_delete);
$stmt_select->execute();
$result = $stmt_select->get_result();
if ($pet = $result->fetch_assoc()) {
    $image_path_to_delete = $pet['image_path'];

    if (!empty($image_path_to_delete) && file_exists('../../' . $image_path_to_delete)) {
        unlink('../../' . $image_path_to_delete);
    }
}
$stmt_select->close();

$sql = "DELETE FROM pets WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $pet_id_to_delete);

if ($stmt->execute()) {
    header('Location: ../adminAnimals.php?status=deleted');
    exit;
} else {
    header('Location: ../adminAnimals.php?status=error&msg=' . urlencode($stmt->error));
    exit;
}

$stmt->close();
$conn->close();
