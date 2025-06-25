<?php

require_once __DIR__ . '/../database/db.php';

class UserRepository {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getUserById($id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }

    public function getPetsByUser($userId): array {
        $stmt = $this->conn->prepare("SELECT * FROM pets WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
