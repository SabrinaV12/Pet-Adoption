<?php

session_start();

require_once __DIR__ . '/../../services/PetRequestService.php';
require_once __DIR__ . '/../../database/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$service = new PetRequestService();

try {
    $service->submitRequest($_POST, $_SESSION['user_id']);
    header('Location: /confirmation?from=request');
    exit();
} catch (Exception $e) {
    http_response_code(500);
    echo "Something went wrong: " . $e->getMessage();
    exit();
}
