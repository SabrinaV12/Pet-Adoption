<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../services/AdoptionService.php';
require_once __DIR__ . '/../services/JwtService.php';

class SubmitAdoptionController
{
    private $adoptionService;

    public function __construct()
    {
        $this->adoptionService = new AdoptionService();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'This endpoint only accepts POST requests.']);
            return;
        }

        $jwtService = new JwtService();
        $token = $jwtService->getToken();
        $userdata = $jwtService->verifyToken($token);

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data provided.']);
            return;
        }

        $applicantId = $userdata->data->id;
        $result = $this->adoptionService->submitApplication($data, $applicantId);

        if ($result['success']) {
            http_response_code(201); // 201 Created
            echo json_encode(['message' => 'Application submitted successfully!', 'app_id' => $result['success']]);
        } else {
            $statusCode = isset($result['errors']['database']) ? 500 : 400;
            http_response_code($statusCode);
            echo json_encode(['error' => 'Application submission failed.', 'details' => $result['errors']]);
        }
    }
}

$controller = new SubmitAdoptionController();
$controller->handleRequest();
