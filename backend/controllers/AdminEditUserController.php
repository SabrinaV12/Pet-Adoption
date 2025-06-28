<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../services/EditUserService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

try {
    $userRepo = new UserRepository();
    $userService = new UserService($userRepo);

    $userService->updateUserFromAdminPanel($_POST, $_FILES);

    echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
