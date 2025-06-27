<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5500");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit();
}

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/ConfirmationController.php';
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/NotificationController.php';
require_once __DIR__ . '/../controllers/PetController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode("/Pet_Adoption/backend/public/index.php", $uri, 2)[1];

switch ($uri) {
    case '/':
    case '/home':
        $controller = new HomepageController();
        $controller->showHomepage();
        break;

    case '/confirm':
        $controller = new ConfirmationController();
        $controller->showConfirmationPage();
        break;

    case '/login':
        $controller = new LoginController();
        $controller->showLoginForm();
        break;

    case '/notifications':
        $controller = new NotificationController();
        $controller->showUserNotifications();
        exit();

    case '/pet':
        $petId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $controller = new PetController();
        $controller->showPetProfile($petId);
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;
}
