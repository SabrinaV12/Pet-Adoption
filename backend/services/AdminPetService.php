<?php

require_once __DIR__ . '/../repositories/VaccinationRepository.php';
require_once __DIR__ . '/../repositories/FeedingCalendarRepository.php';
require_once __DIR__ . '/../repositories/PetRepository.php';

class AdminPetService
{
    private $petRepository;
    private $vaccinationRepository;
    private $feedingCalendarRepository;

    public function __construct()
    {
        $this->petRepository = new PetRepository();
        $this->vaccinationRepository = new VaccinationRepository();
        $this->feedingCalendarRepository = new FeedingCalendarRepository();
    }

    public function getAllPets()
    {
        return $this->petRepository->getAllPets();
    }

    public function getPetDetailsById(int $id): ?array
    {
        if (empty($id) || !is_numeric($id)) {
            throw new InvalidArgumentException("Invalid Pet ID provided.");
        }

        $pet = $this->petRepository->getPetById($id);

        if (!$pet) {
            return null;
        }

        $vaccinations = $this->vaccinationRepository->findByPetId($pet->getId());
        $feedingSchedules = $this->feedingCalendarRepository->findByPetId($pet->getId());

        return [
            'pet' => $pet,
            'vaccinations' => $vaccinations,
            'feeding_schedules' => $feedingSchedules
        ];
    }

    public function deletePetById(int $id): bool
    {
        if (empty($id) || !is_numeric($id)) {
            throw new InvalidArgumentException("Invalid Pet ID provided.");
        }

        $vaccinesDeleted = $this->vaccinationRepository->deleteByPetId($id);
        $schedulesDeleted = $this->feedingCalendarRepository->deleteByPetId($id);
        $petDeleted = $this->petRepository->delete($id);

        return $vaccinesDeleted && $schedulesDeleted && $petDeleted;
    }
}
