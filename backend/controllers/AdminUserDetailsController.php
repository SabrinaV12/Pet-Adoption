<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/check_admin.php';

class AdminUserDetailsController
{
    private $userRepository;
    private $checkAdminService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->checkAdminService = new JwtService();
    }

    public function showUserDetailsAsApi()
    {

        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: http://localhost:5500");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204); // No Content
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405); //Method not Allowed
            exit();
        }

        $jwt_token = $this->checkAdminService->getBearerToken();
        try {
            $jwt_data = $this->checkAdminService->verifyAdminToken($jwt_token);
            http_response_code(200);
        } catch (Exception $e) {
            echo $e;
            http_response_code(401);
            exit();
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'User ID is required and must be a number.']);
            exit();
        }

        $userId = (int)$_GET['id'];

        $user = $this->userRepository->getUserById($userId);

        if ($user === null) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => "User with ID $userId not found."]);
            exit();
        }

        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'role' => $user->getRole(),
            'description' => $user->getDescription(),
            'country' => $user->getCountry(),
            'county' => $user->getCounty(),
            'telegramHandle' => $user->getTelegramHandle(),
            'profilePicture' => $user->getProfilePicture(),
            'bannerPicture' => $user->getBannerPicture()
        ];

        http_response_code(200); // OK
        echo json_encode($userData);
    }
}

$controller = new AdminUserDetailsController();
$controller->showUserDetailsAsApi();
