<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/check_admin.php';

class AdminUserController
{
    private $userRepository;
    private $checkAdminService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->checkAdminService = new JwtService();
    }

    public function showUserListAsApi()
    {
        $users = $this->userRepository->getAllUsers();

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
            http_response_code(405);
            exit();
        }

        $jwt_token = $this->checkAdminService->getBearerToken();
        try {
            // $jwt_data = $this->checkAdminService->verifyAdminToken($jwt_token);
            http_response_code(200);

            echo json_encode($users);
        } catch (Exception $e) {
            echo $e;
            http_response_code(401);
            exit();
        }
    }
}

$controller = new AdminUserController();
$controller->showUserListAsApi();
