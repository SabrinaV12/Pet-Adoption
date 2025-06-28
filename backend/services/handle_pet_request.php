<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();

$jwt = $_COOKIE['jwt'] ?? null;
$secret_key = $_ENV['JWT_SECRET'] ?? 'SECRET_KEY';

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $userId = $decoded->data->id ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - invalid token.']);
            exit();
        }

        $_SESSION['user_id'] = $userId;

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized - token error.']);
        exit();
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized - token missing.']);
    exit();
}


header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../repositories/database/db.php';
require_once __DIR__ . '/../repositories/PetRequestRepository.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$data = $_POST;

$data['vaccinated'] = $data['vaccinated'] ?? 0;
$data['house_trained'] = $data['house_trained'] ?? 0;
$data['neutered'] = $data['neutered'] ?? 0;
$data['microchipped'] = $data['microchipped'] ?? 0;
$data['good_with_children'] = $data['good_with_children'] ?? 0;
$data['shots_up_to_date'] = $data['shots_up_to_date'] ?? 0;

$imagePath = null;
if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../public/PetUploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = uniqid('pet_') . '_' . basename($_FILES['pet_image']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['pet_image']['tmp_name'], $targetFile)) {
        $imagePath = '/Pet_Adoption/public/PetUploads/' . $filename;
        $data['image_path'] = $imagePath;
    }
}

$repo = new PetRequestRepository();
$requestId = $repo->insertRequest($data, $userId);

$feedDates = $_POST['feed_date'] ?? [];
$foodTypes = $_POST['food_type'] ?? [];
foreach ($feedDates as $i => $date) {
    $food = $foodTypes[$i] ?? '';
    if (!empty($date) && !empty($food)) {
        $repo->insertFeeding($requestId, $date, $food);
    }
}

$ages = $_POST['age_in_weeks'] ?? [];
$vaccines = $_POST['vaccine_name'] ?? [];
foreach ($ages as $i => $age) {
    $name = $vaccines[$i] ?? '';
    if (!empty($age) && !empty($name)) {
        $repo->insertVaccination($requestId, (int)$age, $name);
    }
}

foreach ($repo->getAdmins() as $admin) {
    $repo->notifyAdmin($admin['id'], "New pet adoption request submitted", "/admin/view_request.php?id=$requestId");
}

echo json_encode(['success' => true, 'id' => $requestId]);
