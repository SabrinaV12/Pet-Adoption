<?php

require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/../models/feeding_calendar.php';

class FeedingCalendarRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function findByPetId(int $petId): array
    {
        $schedules = [];
        $sql = "SELECT * FROM feeding_calendar WHERE pet_id = ? ORDER BY feed_date ASC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return [];
        }

        $stmt->bind_param("i", $petId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $schedules[] = new FeedingCalendar(
                $row['id'],
                $row['pet_id'],
                $row['feed_date'],
                $row['food_type']
            );
        }

        $stmt->close();
        return $schedules;
    }

    public function add(int $petId, string $feedDate, string $foodType): bool
    {
        $sql = "INSERT INTO feeding_calendar (pet_id, feed_date, food_type) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $petId, $feedDate, $foodType);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function deleteByPetId(int $petId): bool
    {
        $sql = "DELETE FROM feeding_calendar WHERE pet_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("i", $petId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
