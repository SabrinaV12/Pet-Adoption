<?php
session_start();
require_once '../repositories/database/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$filePath = $data['file_path'] ?? '';

if (!$filePath) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing file path']);
    exit;
}

$userId = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("
  SELECT pm.id, pm.file_path
  FROM pet_media pm
  JOIN pets p ON pm.pet_id = p.id
  WHERE pm.file_path = ? AND p.user_id = ?
");
$stmt->bind_param("si", $filePath, $userId);
$stmt->execute();
$result = $stmt->get_result();
$media = $result->fetch_assoc();
$stmt->close();

if (!$media) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized to delete this file']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM pet_media WHERE file_path = ?");
$stmt->bind_param("s", $filePath);
$stmt->execute();
$stmt->close();

$fullPath = __DIR__ . '/../../public/pet-uploads/' . $filePath;
if (file_exists($fullPath)) {
    unlink($fullPath);
}

echo json_encode(['success' => true]);
exit;
?>
