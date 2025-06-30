<?php

require_once __DIR__ . '/../services/AdminAddUserService.php';
require_once __DIR__ . '/../services/JwtService.php';

class AdminAddUserController
{
    private $checkAdminService;
    private $addUserService;

    public function __construct()
    {
        $this->checkAdminService = new JwtService();
        $this->addUserService = new AdminAddUserService();
    }

    public function addUser()
    {
        try {
            $jwt_token = $this->checkAdminService->getToken();
            $this->checkAdminService->verifyAdminToken($jwt_token);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authentication failed: ' . $e->getMessage()]);
            exit();
        }

        try {
            $newUser = $this->addUserService->createUserByAdmin($_POST, $_FILES);

            http_response_code(201); // Created
            echo json_encode(['message' => "User '{$newUser->username}' created successfully."]);
        } catch (Exception $e) {
            $statusCode = match ($e->getMessage()) {
                'password_mismatch', 'Field is required' => 400, // Bad Request
                'email_exists', 'username_exists' => 409, // Conflict
                default => 500, // Internal Server Error
            };

            http_response_code($statusCode);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}
