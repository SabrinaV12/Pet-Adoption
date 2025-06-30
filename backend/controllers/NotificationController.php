<?php

require_once __DIR__ . '/../repositories/NotificationRepository.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../repositories/database/db.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class NotificationController {
   public function showUserNotifications() {
    if (!isset($_COOKIE['jwt'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized - No token']);
        exit();
    }

    $jwt = $_COOKIE['jwt'];
    $secret_key = $_ENV['JWT_SECRET'] ?? 'SECRET_KEY';

    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $userId = $decoded->data->id;

        $repo = new NotificationRepository();
        $notifications = $repo->getUserNotifications($userId);
        $repo->markAllAsRead($userId);

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($notifications);
        exit();

    } catch (Exception $e) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid token', 'details' => $e->getMessage()]);
        exit();
    }
}

}

