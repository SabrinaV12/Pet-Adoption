<?php

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode("/Pet_Adoption/backend/api/index.php", $uri, 2)[1];

require_once __DIR__ . '/../controllers/RegisterController.php';
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/LogoutController.php';
require_once __DIR__ . '/../controllers/AuthMeController.php';
require_once __DIR__ . '/../controllers/AdminUserController.php';
require_once __DIR__ . '/../controllers/AdminAddUserController.php';
require_once __DIR__ . '/../controllers/AdminUserDetailsController.php';
require_once __DIR__ . '/../controllers/NotificationController.php';

$path2 = explode("/", $path, 3)[1];

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
                    exit();

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
                        $adminAddUserController = new AdminAddUserController();
                        switch ($method) {
                            case 'GET':
                                $adminUserController->handleGet();
                                break;
                            case 'DELETE':
                                $adminUserController->handleDelete();
                                break;
                            case 'POST':
                                $adminAddUserController->addUser();
                                break;
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
                    }
                    break;

                default:
                    http_response_code(404);
                    echo json_encode(['message' => "404 - Page not found"]);
                    break;
            }
            break;
        }

    case 'notifications': {
            if ($method !== 'GET') {
                http_response_code(405);
                echo json_encode(['message' => "405 - Method not allowed"]);
                exit();
            }

            $notificationController = new NotificationController();
            $notificationController->showUserNotifications();
            break;
        }

        error_log(" ROUTER PATH2: " . $path2);

    case 'request': {
            $subPath = explode("/request/", $path, 2)[1];
            list($rid, $action) = array_pad(explode("/", $subPath), 2, null);

            require_once __DIR__ . '/../controllers/RequestController.php';

            $ctrl = new RequestController();

            if ($action === null && $method === 'GET') {
                $ctrl->getRequestDetails($rid);
            } elseif ($action === 'accept' && $method === 'POST') {
                $ctrl->accept($rid);
            } elseif ($action === 'deny' && $method === 'POST') {
                $ctrl->deny($rid);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not found']);
            }

            break;
        }


    default:
        http_response_code(404);
        echo json_encode(['message' => "404 - Page not found"]);
        break;
}
