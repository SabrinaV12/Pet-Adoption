<?php

require_once __DIR__ . '/../services/AdminPetService.php';
require_once __DIR__ . '/../services/JwtService.php';

class AdminPetController
{
    private $petService;
    private $checkAdminService;

    public function __construct()
    {
        $this->petService = new AdminPetService();
        $this->checkAdminService = new JwtService();
    }

    public function handleRequest()
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: http://localhost:5500");
        header("Access-Control-Allow-Methods: GET, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'OPTIONS') {
            http_response_code(204);
            exit();
        }

        try {
            $jwt_token = $this->checkAdminService->getToken();
            $this->checkAdminService->verifyAdminToken($jwt_token);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authentication failed: ' . $e->getMessage()]);
            exit();
        }

        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
                break;
        }
    }

    private function handleGet()
    {
        try {
            $pets = $this->petService->getAllPets();
            http_response_code(200);
            echo json_encode($pets);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    private function handleDelete()
    {
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id) || !is_numeric($data->id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Valid Pet ID is required.']);
            exit();
        }

        try {
            $petId = (int)$data->id;
            $success = $this->petService->deletePetById($petId);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Pet deleted successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete pet from the database.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

$controller = new AdminPetController();
$controller->handleRequest();
