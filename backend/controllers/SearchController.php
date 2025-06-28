<?php

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../repositories/database/db.php';
require_once __DIR__ . '/../repositories/SearchRepository.php';

session_start();

$repository = new SearchRepository($conn);

$filters = $_GET;

if (isset($filters['type[]'])) {
    $filters['type'] = $filters['type[]'];
    unset($filters['type[]']);
}

$result = $repository->getFilteredPets($filters);

header('Content-Type: application/json');
echo json_encode(array_values($result));
exit;
