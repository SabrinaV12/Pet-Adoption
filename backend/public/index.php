<?php
session_start();

require_once '../../backend/controllers/HomepageController.php';
require_once '../../backend/controllers/ConfirmationController.php';
require_once '../../backend/controllers/LoginController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
        break;
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
