<?php

require_once __DIR__ . '/../repositories/NotificationRepository.php';

class NotificationController {
    public function showUserNotifications() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $repo = new NotificationRepository();

        $notifications = $repo->getUserNotifications($userId);
        $repo->markAllAsRead($userId);

        include __DIR__ . '/../../frontend/view/notifications.php';
    }
}
