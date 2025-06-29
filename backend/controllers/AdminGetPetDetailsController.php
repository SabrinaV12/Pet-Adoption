<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../services/AdminPetService.php';

function json_response(int $statusCode, $data)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    json_response(400, ['error' => 'Bad Request: A valid pet ID must be provided.']);
}

$petId = (int)$_GET['id'];

$adminPetService = new AdminPetService();

try {
    $petDetails = $adminPetService->getPetDetailsById($petId);

    if ($petDetails === null) {
        json_response(404, ['error' => "Not Found: Pet with ID {$petId} not found."]);
    } else {
        json_response(200, $petDetails);
    }
} catch (InvalidArgumentException $e) {
    json_response(400, ['error' => "Bad Request: {$e->getMessage()}"]);
} catch (Exception $e) {
    error_log($e->getMessage());
    json_response(500, ['error' => 'Internal Server Error: An unexpected error occurred.']);
}
