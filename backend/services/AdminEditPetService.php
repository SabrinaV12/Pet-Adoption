<?php

require_once __DIR__ . '/../repositories/PetRepository.php';
require_once __DIR__ . '/../repositories/VaccinationRepository.php';
require_once __DIR__ . '/../repositories/FeedingCalendarRepository.php';

class AdminEditPetService
{
    private $petRepository;
    private $vaccinationRepository;
    private $feedingCalendarRepository;
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->petRepository = new PetRepository();
        $this->vaccinationRepository = new VaccinationRepository();
        $this->feedingCalendarRepository = new FeedingCalendarRepository();
    }

    public function getPetDetails(int $petId): ?array
    {
        $pet = $this->petRepository->getPetById($petId);
        if (!$pet) {
            return null;
        }

        $vaccinations = $this->vaccinationRepository->findByPetId($petId);
        $feedingSchedules = $this->feedingCalendarRepository->findByPetId($petId);

        return [
            'pet' => $pet->toArray(),
            'vaccinations' => array_map(fn($v) => $v->toArray(), $vaccinations),
            'feeding_calendar' => array_map(fn($f) => $f->toArray(), $feedingSchedules)
        ];
    }

    public function updatePetDetails(array $data, array $files): bool
    {
        $this->conn->begin_transaction();

        try {
            $pet = $this->petRepository->getPetById($data['id']);
            if (!$pet) {
                throw new Exception("Pet with ID {$data['id']} not found.");
            }

            $newImagePath = $this->handleImageUpload($files['image'] ?? null, $pet->getImagePath());
            $pet->setImagePath($newImagePath);

            $pet->setName($data['name']);
            $pet->setAnimalType($data['animal_type']);
            $pet->setBreed($data['breed']);
            $pet->setGender($data['gender']);
            $pet->setAge((int)$data['age']);
            $pet->setColor($data['color']);
            $pet->setWeight((float)$data['weight']);
            $pet->setHeight((float)$data['height']);
            $pet->setSize($data['size']);
            $pet->setDescription($data['description'] ?? '');
            $pet->setRestrictions($data['restrictions'] ?? '');
            $pet->setRecommended($data['recommended'] ?? '');

            $pet->setVaccinated(isset($data['vaccinated']) ? 1 : 0);
            $pet->setNeutered(isset($data['neutered']) ? 1 : 0);
            $pet->setMicrochipped(isset($data['microchipped']) ? 1 : 0);
            $pet->setGoodWithChildren(isset($data['good_with_children']) ? 1 : 0);
            $pet->setShotsUpToDate(isset($data['shots_up_to_date']) ? 1 : 0);
            $pet->setHouseTrained(isset($data['house_trained']) ? 1 : 0);

            $isAdopted = isset($data['adopted']) ? 1 : 0;
            $pet->setAdopted($isAdopted);
            $pet->setAdoptionDate($isAdopted && !empty($data['adoption_date']) ? $data['adoption_date'] : null);

            $this->petRepository->update($pet);

            $this->vaccinationRepository->deleteByPetId($data['id']);
            if (isset($data['vaccine_name']) && is_array($data['vaccine_name'])) {
                for ($i = 0; $i < count($data['vaccine_name']); $i++) {
                    $vaccineName = trim($data['vaccine_name'][$i]);
                    $ageInWeeks = (int)$data['age_in_weeks'][$i];
                    if (!empty($vaccineName) && $ageInWeeks > 0) {
                        $this->vaccinationRepository->add($data['id'], $ageInWeeks, $vaccineName);
                    }
                }
            }

            $this->feedingCalendarRepository->deleteByPetId($data['id']);
            if (isset($data['feed_date']) && is_array($data['feed_date'])) {
                for ($i = 0; $i < count($data['feed_date']); $i++) {
                    $feedDate = trim($data['feed_date'][$i]);
                    $foodType = trim($data['food_type'][$i]);
                    if (!empty($feedDate) && !empty($foodType)) {
                        $this->feedingCalendarRepository->add($data['id'], $feedDate, $foodType);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Pet Update Failed: " . $e->getMessage());
            return false;
        }
    }

    private function handleImageUpload(?array $fileData, string $currentPath): string
    {
        if (!$fileData || $fileData['error'] !== UPLOAD_ERR_OK) {
            return $currentPath;
        }

        $uploadDir = __DIR__ . '/../../public/pet-profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
        $uniqueFilename = bin2hex(random_bytes(8)) . '-' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $uniqueFilename;

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExtension, $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.");
        }

        if (move_uploaded_file($fileData['tmp_name'], $uploadPath)) {
            return '/pet-profile/' . $uniqueFilename;
        } else {
            throw new Exception("Failed to move uploaded file.");
        }
    }
}
