<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    die("Not logged in.");
}

$repo = new UserRepository();

$user = $repo->getUserById($userId);
// $pets = $repo->getPetsByUser($userId);

if (!$user) {
    die("User not found.");
}

include __DIR__ . '/../../frontend/pages/userProfile.php';
