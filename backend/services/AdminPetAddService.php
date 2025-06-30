<?php

require_once __DIR__ . '/../repositories/PetRepository.php';
require_once __DIR__ . '/../repositories/VaccinationRepository.php';
require_once __DIR__ . '/../repositories/FeedingCalendarRepository.php';
require_once __DIR__ . '/../services/JwtService.php';

class PetService
{
    private PetRepository $petRepo;
    private VaccinationRepository $vaccinationRepo;
    private FeedingCalendarRepository $feedingRepo;

    public function __construct()
    {
        $this->petRepo = new PetRepository();
        $this->vaccinationRepo = new VaccinationRepository();
        $this->feedingRepo = new FeedingCalendarRepository();
    }

    public function createPet(array $petData, array $fileData): bool
    {
        $imagePath = $this->handleImageUpload($fileData['image']);
        if ($imagePath === null) {
            error_log("Image upload failed.");
            return false;
        }
        $petData['image_path'] = $imagePath;
        global $conn;
        $conn->begin_transaction();

        try {
            $jwt_service = new JwtService();
            $token = $jwt_service->getToken();
            $userData = $jwt_service->verifyAdminToken($token);
            $newPetId = $this->petRepo->create($petData, $userData->data->id);

            if (!$newPetId) {
                throw new Exception("Failed to create pet record.");
            }

            if (!empty($petData['vaccine_name'])) {
                foreach ($petData['vaccine_name'] as $index => $vaccineName) {
                    $ageInWeeks = $petData['age_in_weeks'][$index];
                    if (!empty($vaccineName) && !empty($ageInWeeks)) {
                        $this->vaccinationRepo->add($newPetId, (int)$ageInWeeks, $vaccineName);
                    }
                }
            }

            if (!empty($petData['food_type'])) {
                foreach ($petData['food_type'] as $index => $foodType) {
                    $feedDate = $petData['feed_date'][$index];
                    if (!empty($foodType) && !empty($feedDate)) {
                        $this->feedingRepo->add($newPetId, $feedDate, $foodType);
                    }
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Pet creation failed: " . $e->getMessage());
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            return false;
        }
    }


    private function handleImageUpload(array $imageFileData): ?string
    {
        if ($imageFileData['error'] !== UPLOAD_ERR_OK) {
            return '/public/pet-uploads/pet-profile/default.png';
        }

        $uploadDir = __DIR__ . '/../../public/pet-uploads/pet-profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '-' . basename($imageFileData['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($imageFileData['tmp_name'], $targetPath)) {
            return 'pet-profile/' . $fileName;
        }

        return null;
    }
}
