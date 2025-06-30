<?php
require_once __DIR__ . '/../repositories/database/db.php';

header('Content-Type: application/json');

$petId = isset($_GET['pet_id']) ? intval($_GET['pet_id']) : 0;
if (!$petId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing or invalid pet_id"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
$stmt->bind_param("i", $petId);
$stmt->execute();
$petResult = $stmt->get_result();
$pet = $petResult->fetch_assoc();
$stmt->close();

if (!$pet) {
    http_response_code(404);
    echo json_encode(["error" => "Pet not found"]);
    exit();
}

$location = [];
$stmt = $conn->prepare("SELECT country, county FROM users WHERE id = ?");
$stmt->bind_param("i", $pet['user_id']);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $location = $row;
}
$stmt->close();

$stmt = $conn->prepare("SELECT age_in_weeks, vaccine_name FROM vaccinations WHERE pet_id = ? ORDER BY age_in_weeks");
$stmt->bind_param("i", $petId);
$stmt->execute();
$vaccines = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare("SELECT feed_date, food_type FROM feeding_calendar WHERE pet_id = ? ORDER BY feed_date");
$stmt->bind_param("i", $petId);
$stmt->execute();
$feedings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare("SELECT file_path, file_type FROM pet_media WHERE pet_id = ?");
$stmt->bind_param("i", $petId);
$stmt->execute();
$media = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    "pet" => $pet,
    "location" => $location,
    "vaccines" => $vaccines,
    "feedings" => $feedings,
    "media" => $media
]);
exit();
