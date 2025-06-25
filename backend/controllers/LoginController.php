<?php
class LoginController
{
    public function showLoginForm() {}
}


// session_start();
$method = $_SERVER['REQUEST_METHOD'];
// echo 'saluuuuut';
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
            $username = $input['username'];
            $password = $input['password'];

            if (!$username || !$password) {
                http_response_code(400);
                exit;
            }

            $authService = new AuthService();
            $success = $authService->login($username, $password);
            if ($success == null) {
                http_response_code(500);
                exit;
            }
            if ($success) {
                http_response_code(200);
                exit;
            } else {
                http_response_code(401);
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo $e;
        }
        break;
}
