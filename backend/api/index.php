<?php

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
// header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode("/Pet_Adoption/backend/api/index.php", $uri, 2)[1];

// echo $path;

// http://localhost/Pet_Adoption/backend/api/index.php/

// /auth/register - POST    x
// /auth/login - POST       x
// /auth/me - GET           x
// /auth/logout - POST      x

// /admin/user - GET lista cu toti                              x
// /admin/user?id=USER_ID DELETE                                x
// /admin/user?id=USER_ID PUT                                   trb
// /admin/user/details?id=USER_ID - GET - AdminUserDetails      x


// /admin/pet - GET 
// /admin/pet?id=PET_ID DELETE
// /admin/pet?id=PET_ID PUT

// /admin/pet/details?id=PET_ID - GET


//...

require_once __DIR__ . '/../controllers/RegisterController.php';
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/LogoutController.php';
require_once __DIR__ . '/../controllers/AuthMeController.php';
require_once __DIR__ . '/../controllers/AdminUserController.php';
require_once __DIR__ . '/../controllers/AdminUserDetailsController.php';

$path2 = explode("/", $path, 3)[1];

// echo $path2;

switch ($path2) {

    case 'auth': {
            $path = explode("/auth/", $path, 2)[1];
            switch ($path) {
                case 'register':
                    if ($method !== 'POST') {
                        http_response_code(405);
                        echo json_encode(['message' => "405 - Method not allowed"]);
                        exit();
                    }
                    $registerController = new RegisterController();
                    $registerController->registerUser($_POST, $_FILES);
                    break;

                case 'login':
                    if ($method !== 'POST') {
                        http_response_code(405);
                        echo json_encode(['message' => "405 - Method not allowed"]);
                        exit();
                    }
                    $loginController = new LoginController();
                    $loginController->login();
                    break;

                case 'me':
                    if ($method !== 'GET') {
                        http_response_code(405);
                        echo json_encode(['message' => "405 - Method not allowed"]);
                        exit();
                    }
                    $meController = new AuthMeController();
                    $meController->whoami();
                    break;

                case 'logout':
                    if ($method !== 'POST') {
                        http_response_code(405);
                        echo json_encode(['message' => "405 - Method not allowed"]);
                        exit();
                    }
                    $logoutController = new LogoutController();
                    $logoutController->logout();
                    break;

                default:
                    http_response_code(404);
                    echo json_encode(['message' => "404 - Page not found"]);
                    break;
            }
            break;
        }
    case 'admin': {

            $path2 = explode("/admin/", $path, 2)[1];
            $path3 = explode("/", $path2, 2)[0];
            switch ($path3) {
                case 'user':
                    $path = explode("/admin/user/", $path, 2)[1];
                    if ($path === '') {
                        $adminUserController = new AdminUserController();
                        switch ($method) {
                            case 'GET':
                                $adminUserController->handleGet();
                                break;
                            case 'DELETE':
                                $adminUserController->handleDelete();
                                break;
                            //TODO PUT UPDATE
                            default:
                                http_response_code(405);
                                echo json_encode(['message' => "405 - Method not allowed"]);
                                exit();
                        }
                    } else if ($path !== 'details') {
                        http_response_code(404);
                        echo json_encode(['message' => "404 - Page not found"]);
                        exit();
                    } else {
                        if ($method !== 'GET') {
                            http_response_code(405);
                            echo json_encode(['message' => "405 - Method not allowed"]);
                            exit();
                        }
                        $adminUserDetailsController = new AdminUserDetailsController();
                        $adminUserDetailsController->showUserDetailsAsApi();
                        break;
                    }

                    break;
                default:
                    http_response_code(404);
                    echo json_encode(['message' => "404 - Page not found"]);
                    break;
            }
            break;
        }

    default:
        http_response_code(404);
        echo json_encode(['message' => "404 - Page not found"]);
        break;
}
