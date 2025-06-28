<?php

require_once __DIR__ . '/../repositories/PetRepository.php';
require_once __DIR__ . '/../services/JwtService.php';

class AdminPetDetailsController
{
    private $petRepository;
    private $checkAdminService;

    public function __construct()
    {
        $this->petRepository = new PetRepository();
        $this->checkAdminService = new JwtService();
    }

    public function showPetDetailsAsApi()
    {

        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: http://localhost:5500");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204); // No Content
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405); //Method not Allowed
            exit();
        }

        $jwt_token = $this->checkAdminService->getToken();
        try {
            $jwt_data = $this->checkAdminService->verifyAdminToken($jwt_token);
            http_response_code(200);
        } catch (Exception $e) {
            echo $e;
            http_response_code(401);
            exit();
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Pet ID is required and must be a number.']);
            exit();
        }

        $petId = (int)$_GET['id'];

        $pet = $this->petRepository->getPetById($petId);

        if ($pet === null) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => "Pet with ID $petId not found."]);
            exit();
        }

        $petData = [
            'id' => $pet->getId(),
            'name' => $pet->getName(),
            'breed' => $pet->getBreed(),
            'gender' => $pet->getGender(),
            'age' => $pet->getAge(),
            'color' => $pet->getColor(),
            'weight' => $pet->getWeight(),
            'height' => $pet->getHeight(),
            'animal_type' => $pet->getAnimalType(),
            'image_path' => $pet->getImagePath(),
            'size' => $pet->getSize(),
            'vaccinated' => $pet->getVaccinated(),
            'house_trained' => $pet->getHouseTrained(),
            'neutered' => $pet->getNeutered(),
            'microchipped' => $pet->getMicrochipped(),
            'good_with_children' => $pet->getGoodWithChildren(),
            'shots_up_to_date' => $pet->getShotsUpToDate(),
            'restrictions' => $pet->getRestrictions(),
            'recommended' => $pet->getRecommended(),
            'adopted' => $pet->getAdopted(),
            'adoption_date' => $pet->getAdoptionDate(),
            'description' => $pet->getDescription(),
            'user_id' => $pet->getUserId(),
            'created_at' => $pet->getCreatedAt()
        ];

        http_response_code(200); // OK
        echo json_encode($petData);
    }
}

$controller = new AdminPetDetailsController();
$controller->showPetDetailsAsApi();
