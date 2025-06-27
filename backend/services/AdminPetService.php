<?php

require_once __DIR__ . '/../repositories/PetRepository.php';

class AdminPetService
{
    private $petRepository;

    public function __construct()
    {
        $this->petRepository = new PetRepository();
    }

    public function getAllPets()
    {
        return $this->petRepository->getAllPets();
    }

    public function deletePetById(int $id): bool
    {
        if (empty($id) || !is_numeric($id)) {
            throw new InvalidArgumentException("Invalid Pet ID provided.");
        }

        return $this->petRepository->delete($id);
    }
}
