<?php

$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: http://localhost:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

if ($method !== 'POST') {
    http_response_code(405);
    exit();
}

$cookie_name = "jwt";
$cookie_path = "/";
$cookie_domain = "localhost";

setcookie($cookie_name, "", time() - 3600, $cookie_path, $cookie_domain, false, true);

// Unset session:
// session_start();
// session_unset();
// session_destroy();

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Logged out and cookie cleared']);
