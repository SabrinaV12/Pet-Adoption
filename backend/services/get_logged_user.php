<?php
require_once __DIR__ . '/../services/JwtService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/database/db.php';

header('Content-Type: application/json');

try {
    $jwtService = new JwtService();
    $token = $jwtService->getToken();
    $decoded = $jwtService->verifyToken($token);
    $userId = $decoded->data->id ?? null;

    if (!$userId) {
        throw new Exception("Invalid token payload.");
    }

    $repo = new UserRepository();
    $user = $repo->getUserById($userId);
    $pets = $repo->getPetsByUser($userId);

    echo json_encode([
        'user' => $user,
        'pets' => $pets
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
}
