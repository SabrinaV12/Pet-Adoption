<?php

require_once __DIR__ . '/../repositories/PetRequestRepository.php';

class PetRequestService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new PetRequestRepository();
    }

    public function submitRequest(array $data, int $userId): void
    {
         $imagePath = null;
    if (!empty($_FILES['pet_image']['tmp_name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '_' . basename($_FILES['pet_image']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['pet_image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/' . $filename;
        } else {
            throw new Exception('Failed to upload image.');
        }
    }

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
            'vaccinated' => (int) $data['vaccinated'],
            'house_trained' => (int) $data['house_trained'],
            'neutered' => (int) $data['neutered'],
            'microchipped' => (int) $data['microchipped'],
            'good_with_children' => (int) $data['good_with_children'],
            'shots_up_to_date' => (int) $data['shots_up_to_date'],
            'image_path' => $imagePath,
        ];

        $requestId = $this->repo->insertRequest($basicData, $userId);

        foreach ($data['feed_date'] as $index => $date) {
            $this->repo->insertFeeding($requestId, $date, $data['food_type'][$index]);
        }

        foreach ($data['age_in_weeks'] as $index => $age) {
            $this->repo->insertVaccination($requestId, $age, $data['vaccine_name'][$index]);
        }

        $admins = $this->repo->getAdmins();
        $link = "/admin/animal_pages/pet_requests.php?id=$requestId";
        $message = "A new adoption application has been submitted.";

        foreach ($admins as $admin) {
            $this->repo->notifyAdmin($admin['id'], $message, $link);
        }
    }
}
