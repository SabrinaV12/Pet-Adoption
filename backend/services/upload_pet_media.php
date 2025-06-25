<?php
session_start();
require_once 'database/check_auth.php';
require_once 'database/db.php';

if (!isset($_POST['pet_id'], $_FILES['media'])) {
    die("Missing data.");
}

$petId = intval($_POST['pet_id']);
$userId = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("SELECT user_id FROM pets WHERE id = ?");
$stmt->bind_param("i", $petId);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();
$stmt->close();

if (!$pet || $pet['user_id'] != $userId) {
    die("Unauthorized.");
}

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$file = $_FILES['media'];
$fileTmpPath = $file['tmp_name'];
$fileName = basename($file['name']);
$fileType = mime_content_type($fileTmpPath);

$extension = pathinfo($fileName, PATHINFO_EXTENSION);
$newFileName = uniqid() . "." . $extension;
$destPath = $uploadDir . $newFileName;

if (str_starts_with($fileType, 'image/')) {
    $mediaType = 'image';
} elseif (str_starts_with($fileType, 'video/')) {
    $mediaType = 'video';
} elseif (str_starts_with($fileType, 'audio/')) {
    $mediaType = 'audio';
} else {
    die("Unsupported file type.");
}

if (move_uploaded_file($fileTmpPath, $destPath)) {
    $stmt = $conn->prepare("INSERT INTO pet_media (pet_id, file_path, file_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $petId, $destPath, $mediaType);
    $stmt->execute();
    $stmt->close();
    header("Location: petPage.php?id=$petId");
    exit;
} else {
    die("Upload failed.");
}
?>
