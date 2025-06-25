<?php

session_start();

require_once __DIR__ . '/../../services/AuthService.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$authService = new AuthService();
$success = $authService->login($username, $password);

if ($success) {
    header('Location: /home');
} else {
    header('Location: /login?error=1');
}
exit();
