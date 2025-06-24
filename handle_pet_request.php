<?php
session_start();
require_once 'database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pet_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = $_GET['action'] ?? '';

if (!$pet_id || !in_array($action, ['approve', 'reject'])) {
    die("Invalid request.");
}

$stmt = $conn->prepare("SELECT * FROM pet_requests WHERE id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();

if (!$request) {
    die("Cererea nu a fost gasita.");
}

$user_id = $request['user_id'];
$pet_name = $request['name'];

if ($action === 'approve') {
    $query = "INSERT INTO pets (
        name, gender, breed, age, color, weight, height, animal_type, image_path, size,
        vaccinated, house_trained, neutered, microchipped, good_with_children, shots_up_to_date,
        restrictions, recommended, description, user_id
    ) SELECT 
        name, gender, breed, age, color, weight, height, animal_type, image_path, size,
        vaccinated, house_trained, neutered, microchipped, good_with_children, shots_up_to_date,
        restrictions, recommended, description, user_id
    FROM pet_requests WHERE id = ?";

    $stmt_insert = $conn->prepare($query);
    $stmt_insert->bind_param("i", $pet_id);
    $stmt_insert->execute();
} else {

}

$delete = $conn->prepare("DELETE FROM pet_requests WHERE id = ?");
$delete->bind_param("i", $pet_id);
$delete->execute();

header("Location: notification.php?status=$action");
exit;
?>
