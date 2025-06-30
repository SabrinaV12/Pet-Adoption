<?php

require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/../models/vaccinations.php';

class VaccinationRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function findByPetId(int $petId): array
    {
        $vaccinations = [];
        $sql = "SELECT * FROM vaccinations WHERE pet_id = ? ORDER BY age_in_weeks ASC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return [];
        }

        $stmt->bind_param("i", $petId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $vaccinations[] = new Vaccination(
                $row['id'],
                $row['pet_id'],
                $row['age_in_weeks'],
                $row['vaccine_name']
            );
        }

        $stmt->close();
        return $vaccinations;
    }

    public function add(int $petId, int $ageInWeeks, string $vaccineName): bool
    {
        $sql = "INSERT INTO vaccinations (pet_id, age_in_weeks, vaccine_name) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $petId, $ageInWeeks, $vaccineName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function deleteByPetId(int $petId): bool
    {
        $sql = "DELETE FROM vaccinations WHERE pet_id = ?";
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
