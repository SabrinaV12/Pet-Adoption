<?php

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}


require_once __DIR__ . '/../repositories/SearchRepository.php';
session_start();

$repository = new SearchRepository();
$filters = $_GET;
$result = $repository->getFilteredPets($filters);

header('Content-Type: application/json');

$result = $repository->getFilteredPets($_GET);

echo json_encode(array_values($result)); 

exit;
