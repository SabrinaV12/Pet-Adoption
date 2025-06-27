<?php

require_once __DIR__ . '/../services/RegisterService.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../"); //TO MOVE ON INDEX.PHP!!!
$dotenv->load();

use Firebase\JWT\JWT;

class RegisterController
{
    private $registerService;
    private $userService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
        $this->userService = new UserRepository();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->showRegisterForm();
                break;
            case 'POST':
                $this->registerUser($_POST, $_FILES);
                break;
            default:
                header("Content-Type: application/json");
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                break;
        }
    }

    private function showRegisterForm()
    {
        readfile('../../frontend/view/pages/register.html');
    }

    private function registerUser($postData, $fileData)
    {
        header("Content-Type: application/json");

        try {
            $newUser = $this->registerService->register($postData, $fileData);
            $newUser1 = $this->userService->getUserByUsername($newUser->username);
            $newUserId = $newUser1->id;

            $secret_key = "SECRET_KEY";
            // $issuer_claim = "YOUR_DOMAIN.com";
            // $audience_claim = "YOUR_DOMAIN.com";
            $issuedat_claim = time();
            $notbefore_claim = $issuedat_claim;
            $expire_claim = $issuedat_claim + 3600;

            $payload = [
                // "iss" => $issuer_claim,
                // "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => [
                    "id" => $newUserId,
                    "username" => $newUser->username,
                    "role" => $newUser->role
                ]
            ];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            http_response_code(201);
            echo json_encode([
                'message' => 'User registered successfully.',
                'jwt' => $jwt
            ]);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            $statusCode = 500;

            switch ($errorMessage) {
                case 'password_mismatch':
                case 'Field is required':
                    $statusCode = 400;
                    break;
                case 'email_exists':
                case 'username_exists':
                    $statusCode = 409;
                    break;
                case 'file_upload_error':
                    $statusCode = 500;
                    break;
            }

            http_response_code($statusCode);
            echo json_encode(['message' => $errorMessage]);
        }
    }
}

$controller = new RegisterController();
$controller->handleRequest();
