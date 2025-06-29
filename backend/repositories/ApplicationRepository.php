<?php

require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/../models/applications.php';

class ApplicationRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function save(Applications $app): bool
    {
        $this->conn->begin_transaction();

        try {
            $sql = "INSERT INTO applications (
                pet_id, applicant_id, address_line1, address_line2, postcode, city, phone_number,
                has_garden, living_situation, household_setting, activity_level,
                num_adults, num_children, youngest_child_age,
                visiting_children, visiting_children_ages, has_flatmates,
                has_allergies, has_other_animals, other_animals_info, neutered, vaccinated, experience,
                submitted_at, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }

            $types = "iissssssssiiiisissssssss";

            $stmt->bind_param(
                $types,
                $app->pet_id,
                $app->applicant_id,

                $app->address_line1,
                $app->address_line2,

                $app->postcode,
                $app->city,

                $app->phone_number,

                $app->has_garden,
                $app->living_situation,

                $app->household_setting,
                $app->activity_level,
                $app->num_adults,
                $app->num_children,
                $app->youngest_child_age,

                $app->visiting_children,
                $app->visiting_children_ages,

                $app->has_flatmates,
                $app->has_allergies,

                $app->has_other_animals,
                $app->other_animals_info,
                $app->neutered,
                $app->vaccinated,
                $app->experience,
                $app->status
            );

            $stmt->execute();
            $stmt->close();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            echo $app->num_adults;
            echo "ApplicationRepository::save() failed: " . $e->getMessage();

            return false;
        }
    }

    public function getApplicationForUser(int $applicationId, int $userId): ?array
    {
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
