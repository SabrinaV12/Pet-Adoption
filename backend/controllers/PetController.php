<?php

require_once __DIR__ . '/../repositories/database/db.php';

class PetController {
    public function showPetProfile($petId) {
        global $conn;

        $petQuery = $conn->prepare("SELECT * FROM pets WHERE id = ?");
        $petQuery->bind_param("i", $petId);
        $petQuery->execute();
        $pet = $petQuery->get_result()->fetch_assoc();

        if (!$pet) {
            echo "Pet not found.";
            exit;
        }

        $userLocationQuery = $conn->prepare("SELECT country, county FROM users WHERE id = ?");
        $userLocationQuery->bind_param("i", $pet['user_id']);
        $userLocationQuery->execute();
        $userLocation = $userLocationQuery->get_result()->fetch_assoc();

        $country = $userLocation['country'] ?? 'Unknown';
        $county = $userLocation['county'] ?? 'Unknown';

        $calendarQuery = $conn->prepare("SELECT feed_date, food_type FROM feeding_calendar WHERE pet_id = ? ORDER BY feed_date");
        $calendarQuery->bind_param("i", $petId);
        $calendarQuery->execute();
        $feedings = $calendarQuery->get_result()->fetch_all(MYSQLI_ASSOC);

        $vaccineQuery = $conn->prepare("SELECT age_in_weeks, vaccine_name FROM vaccinations WHERE pet_id = ? ORDER BY age_in_weeks");
        $vaccineQuery->bind_param("i", $petId);
        $vaccineQuery->execute();
        $vaccines = $vaccineQuery->get_result()->fetch_all(MYSQLI_ASSOC);

        $mediaStmt = $conn->prepare("SELECT file_path, file_type FROM pet_media WHERE pet_id = ?");
        $mediaStmt->bind_param("i", $petId);
        $mediaStmt->execute();
        $mediaFiles = $mediaStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $pet['user_id'];

        include __DIR__ . '/../../frontend/pages/pet_profile.php';
    }
}
