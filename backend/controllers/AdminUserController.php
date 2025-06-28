<?php

require_once __DIR__ . '/../services/AdminUserService.php';
require_once __DIR__ . '/../services/JwtService.php';

class AdminUserController
{
    private $userService;
    private $checkAdminService;

    public function __construct()
    {
        $this->userService = new AdminUserService();
        $this->checkAdminService = new JwtService();
    }

    public function handleGet()
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

    public function handleDelete()
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
