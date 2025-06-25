<?php

require_once __DIR__ . '/../database/db.php';

class ApplicationRepository {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getApplicationForUser(int $applicationId, int $userId): ?array {
        $sql = "
            SELECT a.*, p.name as pet_name
            FROM applications a
            JOIN pets p ON a.pet_id = p.id
            WHERE a.id = ? AND p.user_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $applicationId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }
}
