<?php
require_once __DIR__ . '/../repositories/ApplicationRepository.php';

header('Content-Type: application/json');

$appId = $_GET['app_id'] ?? null;
if (!$appId || !is_numeric($appId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing app_id']);
    exit;
}

$repo = new ApplicationRepository();
$application = $repo->getApplicationDetails(intval($appId));

if (!$application) {
    http_response_code(404);
    echo json_encode(['error' => 'Application not found']);
    exit;
}

echo json_encode($application);
