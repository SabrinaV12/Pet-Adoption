<?php
class LoginController
{
    public function showLoginForm() {}
}

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../"); //TO MOVE ON INDEX.PHP!!!
$dotenv->load();

use Firebase\JWT\JWT;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        readfile(__DIR__ . '/../../frontend/view/login.html');
        break;

    case 'POST':
        try {
            require_once __DIR__ . "/../services/AuthService.php";
            header("Access-Control-Allow-Origin: http://localhost:5500");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Allow-Credentials: true");

            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(204); // No Content
                exit();
            }
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
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
