<?php

require_once 'initDB.php';
session_start();

if (isset($_COOKIE['remember_me_token'])) {
    list($selector, $validator) = explode(':', $_COOKIE['remember_me_token'], 2);

    $sql = "DELETE FROM auth_tokens WHERE selector = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $selector);
    $stmt->execute();
}

setcookie('remember_me_token', '', time() - 3600, '/');

$_SESSION = array();
session_destroy();

header("location: ../login.php");
exit;
