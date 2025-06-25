<?php

session_start();
require_once __DIR__ . '/../../services/RegisterService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

try {
    $service = new RegisterService();
    $service->register($_POST, $_FILES);
    header('Location: /login.php?success=registered');
    exit();
} catch (Exception $e) {
    header('Location: /register.php?error=' . $e->getMessage());
    exit();
} 
