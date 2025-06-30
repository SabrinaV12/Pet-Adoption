<?php
session_start();
require_once 'JwtService.php';
require_once '../repositories/database/db.php';
header('Content-Type: application/json');

$data     = json_decode(file_get_contents("php://input"), true);
$filePath = $data['file_path'] ?? '';
if (! $filePath) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing file path']);
  exit;
}

$jwtService = new JwtService();
$token      = $jwtService->getToken();
$payload    = $jwtService->verifyToken($token);
if (! $payload || ! isset($payload->data->id)) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid or missing token']);
  exit;
}
$userId = $payload->data->id;

$stmt = $conn->prepare("
  SELECT pm.id
    FROM pet_media pm
    JOIN pets       p  ON pm.pet_id = p.id
   WHERE pm.file_path = ?
     AND p.user_id   = ?
");
$stmt->bind_param("si", $filePath, $userId);
$stmt->execute();
if (! $stmt->get_result()->fetch_assoc()) {
  http_response_code(403);
  echo json_encode(['error' => 'Not authorized to delete this file']);
  exit;
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM pet_media WHERE file_path = ?");
$stmt->bind_param("s", $filePath);
$stmt->execute();
$stmt->close();

$fullPath = __DIR__ . '/../../public/pet-uploads/' . $filePath;
if (file_exists($fullPath)) unlink($fullPath);

echo json_encode(['success' => true]);
exit;
