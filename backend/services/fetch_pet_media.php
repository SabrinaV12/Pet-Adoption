<?php
session_start();
require_once 'JwtService.php';
require_once '../repositories/database/db.php';
header('Content-Type: application/json');

$data  = json_decode(file_get_contents("php://input"), true);
$petId = intval($data['pet_id'] ?? 0);

$jwtService = new JwtService();
$payload    = $jwtService->verifyToken($jwtService->getToken());
$userId     = ($payload && isset($payload->data->id))
            ? $payload->data->id 
            : 0;

$stmt = $conn->prepare("
  SELECT pm.file_path, pm.file_type, p.user_id AS owner_id
    FROM pet_media pm
    JOIN pets       p  ON pm.pet_id = p.id
   WHERE p.id = ?
");
$stmt->bind_param("i", $petId);
$stmt->execute();
$res = $stmt->get_result();

$media = [];
while ($row = $res->fetch_assoc()) {
  $row['can_delete'] = (intval($row['owner_id']) === $userId);
  unset($row['owner_id']);
  $media[] = $row;
}
echo json_encode(['media' => $media]);
