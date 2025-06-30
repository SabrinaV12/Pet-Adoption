    <?php

    header('Content-Type: application/json');

    require_once __DIR__ . '/../services/AdminPetAddService.php';
    require_once __DIR__ . '/../services/JwtService.php';


    function json_response($status, $message = '', $data = [])
    {
        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        json_response('error', 'Invalid request method.');
    }

    try {
        $jwtService = new JwtService();
        $token = $jwtService->getToken();
        $jwtService->verifyAdminToken($token);
    } catch (Exception $e) {
        http_response_code(401); // 401 Unauthorized
        json_response('error', $e->getMessage());
    }

    try {
        $postData = $_POST;
        $petData = [
            'name' => trim($postData['name'] ?? ''),
            'animal_type' => $postData['animal_type'] ?? 'Dog',
            'breed' => trim($postData['breed'] ?? ''),
            'gender' => $postData['gender'] ?? 'Male',
            'age' => (int)($postData['age'] ?? 0),
            'color' => trim($postData['color'] ?? ''),
            'weight' => (float)($postData['weight'] ?? 0.0),
            'height' => (float)($postData['height'] ?? 0.0),
            'size' => $postData['size'] ?? 'Medium',
            'description' => trim($postData['description'] ?? ''),
            'restrictions' => trim($postData['restrictions'] ?? ''),
            'recommended' => trim($postData['recommended'] ?? ''),
            'adoption_date' => !empty($postData['adoption_date']) ? $postData['adoption_date'] : null,

            'vaccinated' => isset($postData['vaccinated']) ? 1 : 0,
            'neutered' => isset($postData['neutered']) ? 1 : 0,
            'microchipped' => isset($postData['microchipped']) ? 1 : 0,
            'good_with_children' => isset($postData['good_with_children']) ? 1 : 0,
            'shots_up_to_date' => isset($postData['shots_up_to_date']) ? 1 : 0,
            'house_trained' => isset($postData['house_trained']) ? 1 : 0,
            'adopted' => isset($postData['adopted']) ? 1 : 0,

            'feed_date' => $postData['feed_date'] ?? [],
            'food_type' => $postData['food_type'] ?? [],
            'age_in_weeks' => $postData['age_in_weeks'] ?? [],
            'vaccine_name' => $postData['vaccine_name'] ?? [],
        ];

        $petService = new PetService();
        $success = $petService->createPet($petData, $_FILES);

        if ($success) {
            json_response('success', 'Pet added successfully.');
        } else {
            http_response_code(500);
            json_response('error', 'Failed to save the pet to the database. The service layer might have encountered an issue.');
        }
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Error in AdminAddAnimalController: " . $e->getMessage());
        json_response('error', 'An unexpected server error occurred.');
    }
