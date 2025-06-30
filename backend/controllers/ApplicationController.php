<?php

require_once __DIR__ . '/../repositories/ApplicationRepository.php';
require_once __DIR__ . '/../services/JwtService.php';

$jwtService = new JwtService();
$userId = null;

try {
    $token = $jwtService->getToken();
    $decodedPayload = $jwtService->verifyToken($token);
    $userId = $decodedPayload->data->user_id;
} catch (Exception $e) {
    header('HTTP/1.1 401 Unauthorized');
    die($e->getMessage());
}


$applicationId = isset($_GET['id']) ? intval($_GET['id']) : 0;


$repo = new ApplicationRepository();
$application = $repo->getApplicationForUser($applicationId, $userId);

if (!$application) {
    die("Application not found or access denied.");
}

include __DIR__ . '/../../frontend/pages/view_application.php';
