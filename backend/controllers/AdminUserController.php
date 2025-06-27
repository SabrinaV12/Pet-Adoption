<?php

require_once __DIR__ . '/../services/AdminUserService.php';
require_once __DIR__ . '/../services/check_admin.php';

class AdminUserController
{
    private $userService;
    private $checkAdminService;

    public function __construct()
    {
        $this->userService = new AdminUserService();
        $this->checkAdminService = new JwtService();
    }

    public function handleRequest()
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: http://localhost:5500");
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'OPTIONS') {
            http_response_code(204); // No Content
            exit();
        }

        try {
            $jwt_token = $this->checkAdminService->getBearerToken();
            $this->checkAdminService->verifyAdminToken($jwt_token);
        } catch (Exception $e) {
            http_response_code(401); // Unauthorized
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
            $users = $this->userService->getAllUsers();
            http_response_code(200);
            echo json_encode($users);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    private function handleDelete()
    {
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id) || !is_numeric($data->id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Valid User ID is required.']);
            exit();
        }

        try {
            $userId = (int)$data->id;
            $success = $this->userService->deleteUserById($userId);

            if ($success) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete user from the database.']);
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

$controller = new AdminUserController();
$controller->handleRequest();
