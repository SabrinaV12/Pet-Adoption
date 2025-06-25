<?php

require_once __DIR__ . '/../repositories/PetRequestRepository.php';

class PetRequestService {
    private $repo;

    public function __construct() {
        $this->repo = new PetRequestRepository();
    }

    public function submitRequest(array $data, int $userId): void {
        $basicData = [
            'name' => $data['name'],
            'gender' => $data['gender'],
            'breed' => $data['breed'],
            'age' => (int)$data['age'],
            'color' => $data['color'],
            'weight' => (float)$data['weight'],
            'height' => (float)$data['height'],
            'animal_type' => $data['animal_type'],
            'size' => $data['size'],
            'description' => $data['description'],
            'restrictions' => $data['restrictions'],
            'recommended' => $data['recommended'],
            'vaccinated' => isset($data['vaccinated']),
            'house_trained' => isset($data['house_trained']),
            'neutered' => isset($data['neutered']),
            'microchipped' => isset($data['microchipped']),
            'good_with_children' => isset($data['good_with_children']),
            'shots_up_to_date' => isset($data['shots_up_to_date']),
        ];

        $requestId = $this->repo->insertRequest($basicData, $userId);

        foreach ($data['feed_date'] as $index => $date) {
            $this->repo->insertFeeding($requestId, $date, $data['food_type'][$index]);
        }

        foreach ($data['age_in_weeks'] as $index => $age) {
            $this->repo->insertVaccination($requestId, $age, $data['vaccine_name'][$index]);
        }

        $admins = $this->repo->getAdmins();
        $link = "/admin/animal_pages_scripts/admin_pet_requests.php?id=$requestId";
        $message = "A fost trimisă o nouă cerere de adopție.";

        foreach ($admins as $admin) {
            $this->repo->notifyAdmin($admin['id'], $message, $link);
        }
    }
}
