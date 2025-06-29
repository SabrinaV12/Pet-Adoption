<?php
session_start();
require_once 'JwtService.php';
require_once '../repositories/database/db.php';

if (! isset($_POST['pet_id'], $_FILES['media'])) {
    die("Missing data.");
}

$petId = intval($_POST['pet_id']);

$jwtService = new JwtService();
$token      = $jwtService->getToken();
$payload    = $jwtService->verifyToken($token);

if (! $payload) {
    die("Unauthorized: invalid token.");
}

if (! isset($payload->data->id)) {
    die("Unauthorized: malformed token payload (no user id).");
}
$userId = $payload->data->id;

$stmt = $conn->prepare("SELECT user_id FROM pets WHERE id = ?");
$stmt->bind_param("i", $petId);
$stmt->execute();
$stmt->bind_result($ownerId);
if (! $stmt->fetch() || $ownerId !== $userId) {
    die("Unauthorized: you are not the owner of this pet.");
}
$stmt->close();


$uploadDir = '../../public/pet-uploads/pet-media/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$file = $_FILES['media'];
$fileTmpPath = $file['tmp_name'];
$fileName = basename($file['name']);

$fileType = mime_content_type($fileTmpPath);


$extension = pathinfo($fileName, PATHINFO_EXTENSION);
$newFileName = uniqid() . "." . $extension;
$destPath = $uploadDir . $newFileName;

$relativePath = 'pet-media/' . $newFileName;

if (!str_starts_with($fileType, 'image/') && !str_starts_with($fileType, 'video/') && !str_starts_with($fileType, 'audio/')) {
    die("Unsupported file type.");
}



if (move_uploaded_file($fileTmpPath, $destPath)) {
 
    $fileType = explode("/", $fileType, 2)[0];
    $stmt = $conn->prepare("INSERT INTO pet_media (pet_id, file_path, file_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $petId, $relativePath, $fileType);
    $stmt->execute();
    $stmt->close();

    header("Location: ../../frontend/view/pages/pet_profile.html?pet_id=$petId");
    exit;
} else {
    die("Upload failed.");
}
?>
