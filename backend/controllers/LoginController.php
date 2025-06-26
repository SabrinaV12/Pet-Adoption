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
        require_once '../../backend/database/check_auth.php';
        readfile('../../frontend/view/login.html');
        break;

    case 'POST':
        try {
            require_once __DIR__ . "/../services/AuthService.php";

            header("Content-Type: application/json");
            $input = json_decode(file_get_contents('php://input'), true);
            $username = $input['username'] ?? null;
            $password = $input['password'] ?? null;

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
                        "id" => $user->id,
                        "username" => $user->username,
                        "role" => $user->role,
                    ]
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                http_response_code(200);
                echo json_encode([
                    'message' => 'Login successful.',
                    'jwt' => $jwt
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
