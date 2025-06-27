<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

$token = $_COOKIE['jwt'] ?? null;

$secretKey = 'SECRET_KEY';
$algorithm = 'HS256';

if (!$token) {
    http_response_code(401);
    exit();
}

try {
    if (is_null($token) || empty($token)) {
        throw new Exception('Authentication token was not provided.');
    }
    $decodedPayload = JWT::decode($token, new Key($secretKey, $algorithm));
} catch (Exception $e) {
    echo $e;
    http_response_code(500);
}

echo json_encode($decodedPayload->data);