<?php
session_start();
require_once 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$petId = intval($_POST['pet_id'] ?? 0);
$filePath = $_POST['file_path'] ?? '';

if (!$petId || !$filePath) {
    die("Invalid request");
}

$ownerCheck = $conn->prepare("SELECT user_id FROM pets WHERE id = ?");
$ownerCheck->bind_param("i", $petId);
$ownerCheck->execute();
$result = $ownerCheck->get_result();
$pet = $result->fetch_assoc();

if (!$pet || $pet['user_id'] !== $_SESSION['user_id']) {
    die("Unauthorized to delete this media");
}

$fullPath = __DIR__ . '/' . $filePath;
if (file_exists($fullPath)) {
    unlink($fullPath);
}

$deleteStmt = $conn->prepare("DELETE FROM pet_media WHERE pet_id = ? AND file_path = ?");
$deleteStmt->bind_param("is", $petId, $filePath);
$deleteStmt->execute();

header("Location: petPage.php?id=" . $petId);
exit;
?>
