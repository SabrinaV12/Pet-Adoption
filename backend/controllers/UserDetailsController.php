<?php

require_once __DIR__ . '/../services/JwtService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

header('Content-Type: application/json');

class UserDetailsController
{

    public function getUserDetails()
    {
        try {
            $jwtService = new JwtService();
            $token = $jwtService->getToken();

            if (!$token) {
                http_response_code(401); // Unauthorized
                echo json_encode(['error' => 'Authentication token not provided.']);
                return;
            }

            $details = $jwtService->verifyToken($token);
            if (!$details || !isset($details->data->id)) {
                http_response_code(401); // Unauthorized
                echo json_encode(['error' => 'Invalid or expired authentication token.']);
                return;
            }

            $userRepository = new UserRepository();
            $user = $userRepository->getUserById($details->data->id);

            if ($user === null) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'User not found.']);
                return;
            }

            $userDetailsArray = [
                'id' => $user->getId(),
                'profile_picture' => $user->getProfilePicture(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'phone_number' => $user->getPhoneNumber(),
                'description' => $user->getDescription(),
                'county' => $user->getCounty(),
                'country' => $user->getCountry(),
                'telegram_handle' => $user->getTelegramHandle(),
                'banner_picture' => $user->getBannerPicture()
            ];

            echo json_encode($userDetailsArray);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            error_log($e->getMessage());
            echo json_encode(['error' => 'An internal server error occurred.']);
        }
    }
}

//DE STERS

$userDetailsController = new UserDetailsController();
$userDetailsController->getUserDetails();
