<?php

require_once __DIR__ . '/../services/EditUserService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/JwtService.php';

class AdminEditUserController
{
    private $userService;
    private $jwtService;

    public function __construct(UserService $userService, JwtService $jwtService)
    {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
    }

    public function updateUser()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            return;
        }

        try {
            $token = $this->jwtService->getToken();
            $this->jwtService->verifyAdminToken($token);
            $this->userService->updateUserFromAdminPanel($_POST, $_FILES);

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
        } catch (Exception $e) {
            $statusCode = 500;
            $message = $e->getMessage();

            if ($message === 'Authentication token was not provided.' || $message === 'Invalid token signature. Access denied.') {
                $statusCode = 401; // Unauthorized
            } elseif ($message === 'Provided token has expired. Please log in again.' || $message === 'Access denied. Administrator privileges are required.') {
                $statusCode = 403; // Forbidden
            } elseif ($e->getCode() >= 400 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }

            http_response_code($statusCode);
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    }
}
