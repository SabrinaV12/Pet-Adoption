<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../"); //TO MOVE ON INDEX.PHP!!!
$dotenv->load();

use Firebase\JWT\JWT;

require_once __DIR__ . "/../services/AuthService.php";

class LoginController
{
    public function login()
    {
        try {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$username || !$password) {
                http_response_code(400);
                echo json_encode(['message' => 'Please provide both username and password.']);
                exit;
            }

            $authService = new AuthService();
            $user = $authService->login($username, $password);

            if ($user === null) {
                http_response_code(500);
                echo json_encode(['message' => 'An unexpected error occurred.']);
                exit;
            }

            if ($user) {
                $secret_key = $_ENV['JWT_SECRET'] ?? "SECRET_KEY";
                $issuedat_claim = time();
                $expire_claim = $issuedat_claim + (86400 * 7);
                $payload = [
                    // "iss" => "YOUR_DOMAIN.com", 
                    // "aud" => "YOUR_DOMAIN.com", 
                    "iat" => $issuedat_claim,
                    "nbf" => $issuedat_claim,
                    "exp" => $expire_claim,
                    "data" => [
                        "id" => $user->id,
                        "username" => $user->username,
                        "role" => $user->role,
                    ]
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                setcookie('jwt', $jwt, [
                    'expires' => $expire_claim,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                    'domain' => 'localhost'
                ]);

                http_response_code(200);

                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful.'
                ]);

                exit;
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid username or password.']);
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred during the login process.', 'error' => $e->getMessage()]);
        }
    }
}
