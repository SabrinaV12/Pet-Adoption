<?php

require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/../models/pet.php';

class PetRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * @param int $id
     * @return Pet|null
     */
    public function getPetById(int $id): ?Pet
    {
        $stmt = $this->conn->prepare("SELECT * FROM pets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $petData = $result->fetch_assoc();
        $stmt->close();

        return new Pet(
            $petData['id'],
            $petData['name'],
            $petData['breed'],
            $petData['gender'],
            $petData['age'],
            $petData['color'],
            $petData['weight'],
            $petData['height'],
            $petData['animal_type'],
            $petData['image_path'],
            $petData['size'],
            $petData['vaccinated'],
            $petData['house_trained'],
            $petData['neutered'],
            $petData['microchipped'],
            $petData['good_with_children'],
            $petData['shots_up_to_date'],
            $petData['restrictions'],
            $petData['recommended'],
            $petData['adopted'],
            $petData['adoption_date'],
            $petData['description'],
            $petData['user_id'],
            $petData['created_at']
        );
    }

    /**
     * @return array
     */
    public function getAllPets(): array
    {
        $pets = [];
        $stmt = $this->conn->prepare("SELECT * FROM pets ORDER BY id ASC");

        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pets[] = new Pet(
                    $row['id'],
                    $row['name'],
                    $row['breed'],
                    $row['gender'],
                    $row['age'],
                    $row['color'],
                    $row['weight'],
                    $row['height'],
                    $row['animal_type'],
                    $row['image_path'],
                    $row['size'],
                    $row['vaccinated'],
                    $row['house_trained'],
                    $row['neutered'],
                    $row['microchipped'],
                    $row['good_with_children'],
                    $row['shots_up_to_date'],
                    $row['restrictions'],
                    $row['recommended'],
                    $row['adopted'],
                    $row['adoption_date'],
                    $row['description'],
                    $row['user_id'],
                    $row['created_at']
                );
            }
        }

        $stmt->close();
        return $pets;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM pets WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function findPetsByCriteria(array $filters): array
    {
        $conditions = [];
        $params = [];
        $types = "";

        // Default condition for the RSS feed
        $conditions[] = "adopted = 0";

        if (!empty($filters['type'])) {
            $placeholders = implode(',', array_fill(0, count($filters['type']), '?'));
            $conditions[] = "animal_type IN ($placeholders)";
            $params = array_merge($params, $filters['type']);
            $types .= str_repeat('s', count($filters['type']));
        }

        if (!empty($filters['breed'])) {
            $conditions[] = "breed = ?";
            $params[] = $filters['breed'];
            $types .= 's';
        }

        $sql = "SELECT * FROM pets WHERE " . implode(" AND ", $conditions);
        $sql .= " ORDER BY created_at DESC LIMIT 25";

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $pets = [];

        if ($result->num_rows > 0) {
            while ($petData = $result->fetch_assoc()) {
                $pets[] = new Pet(
                    $petData['id'],
                    $petData['name'],
                    $petData['breed'],
                    $petData['gender'],
                    $petData['age'],
                    $petData['color'],
                    $petData['weight'],
                    $petData['height'],
                    $petData['animal_type'],
                    $petData['image_path'],
                    $petData['size'],
                    $petData['vaccinated'],
                    $petData['house_trained'],
                    $petData['neutered'],
                    $petData['microchipped'],
                    $petData['good_with_children'],
                    $petData['shots_up_to_date'],
                    $petData['restrictions'],
                    $petData['recommended'],
                    $petData['adopted'],
                    $petData['adoption_date'],
                    $petData['description'],
                    $petData['user_id'],
                    $petData['created_at']
                );
            }
        }

        $stmt->close();
        return $pets;
    }
}
