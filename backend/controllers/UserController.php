<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/JwtService.php';

$jwtService = new JwtService();
$userId = null;

try {
    $token = $jwtService->getToken();

    $decodedPayload = $jwtService->verifyToken($token);

    $userId = $decodedPayload->data->user_id;
} catch (Exception $e) {
    header('HTTP/1.1 401 Unauthorized');
    die('Authentication required: ' . $e->getMessage());
}

$repo = new UserRepository();

$user = $repo->getUserById($userId);
$pets = $repo->getPetsByUser($userId);

if (!$user) {
    die("User not found.");
}

include __DIR__ . '/../../frontend/pages/userProfile.php';
