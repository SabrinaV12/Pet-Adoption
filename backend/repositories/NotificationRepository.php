<?php
require_once(__DIR__ . '/database/db.php');


class NotificationRepository {
    public function getUserNotifications($userId) {
        global $conn;

        $stmt = $conn->prepare("SELECT id, message, link, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }

        return $notifications;
    }

    public function markAllAsRead($userId) {
        global $conn;

        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
    }

    public function create(int $userId, string $message, ?string $link = null): void {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $message, $link);
    $stmt->execute();
}

}
