<?php

require_once __DIR__ . '/../repositories/ApplicationRepository.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$applicationId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userId = $_SESSION['user_id'];

$repo = new ApplicationRepository();
$application = $repo->getApplicationForUser($applicationId, $userId);

if (!$application) {
    die("Application not found or access denied.");
}

include __DIR__ . '/../../frontend/pages/view_application.php';