<?php

require_once __DIR__ . '/database/db.php';

class PetRequestRepository {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function insertRequest(array $data, int $userId): int {
        $stmt = $this->conn->prepare("INSERT INTO pet_requests (
            name, gender, breed, age, color, weight, height, animal_type, size, user_id, description,
            restrictions, recommended, vaccinated, house_trained, neutered, microchipped,
            good_with_children, shots_up_to_date, image_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssisdsssisssiiiiiis",
            $data['name'], $data['gender'], $data['breed'], $data['age'], $data['color'],
            $data['weight'], $data['height'], $data['animal_type'], $data['size'], $userId,
            $data['description'], $data['restrictions'], $data['recommended'],
            $data['vaccinated'], $data['house_trained'], $data['neutered'], $data['microchipped'],
            $data['good_with_children'], $data['shots_up_to_date'], $data['image_path']
        );

        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function insertFeeding(int $requestId, string $date, string $type): void {
        $stmt = $this->conn->prepare("INSERT INTO feeding_calendar_requests (request_id, feed_date, food_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $requestId, $date, $type);
        $stmt->execute();
    }

    public function insertVaccination(int $requestId, int $ageInWeeks, string $name): void {
        $stmt = $this->conn->prepare("INSERT INTO vaccinations_requests (request_id, age_in_weeks, vaccine_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $requestId, $ageInWeeks, $name);
        $stmt->execute();
    }

    public function getAdmins(): array {
        $result = $this->conn->query("SELECT id FROM users WHERE role = 'admin'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function notifyAdmin(int $userId, string $message, string $link): void {
        $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $message, $link);
        $stmt->execute();
    }

    
}
