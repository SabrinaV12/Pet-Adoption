<?php
require_once __DIR__ . '/../repositories/ApplicationRepository.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$appId = $data['app_id'] ?? null;
$action = $data['action'] ?? null;

if (!$appId || !in_array($action, ['approve', 'deny'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$repo = new ApplicationRepository();

if ($repo->handleDecision($appId, $action)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error processing decision']);
}
