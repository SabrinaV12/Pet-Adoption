<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../services/adminEditPetService.php';

$service = new AdminEditPetService();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $petId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($petId <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'A valid Pet ID is required.']);
            exit;
        }

        $data = $service->getPetDetails($petId);

        if ($data) {
            http_response_code(200); // OK
            echo json_encode($data);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Pet not found.']);
        }
        break;

    case 'POST':
        $petId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($petId <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid or missing Pet ID in the form data.']);
            exit;
        }
        $success = $service->updatePetDetails($_POST, $_FILES);

        if ($success) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Pet updated successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update pet due to a server error.']);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method not allowed. Please use GET or POST.']);
        break;
}
