<?php

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    return;
}

if (isset($_COOKIE['remember_me_token'])) {

    list($selector, $validator) = explode(':', $_COOKIE['remember_me_token'], 2);

    if ($selector && $validator) {
        require_once 'initDB.php';

        $sql = "SELECT * FROM auth_tokens WHERE selector = ? AND expires >= NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $selector);
        $stmt->execute();
        $result = $stmt->get_result();
        $token_data = $result->fetch_assoc();

        if ($token_data) {
            $hashed_validator_from_cookie = hash('sha256', $validator);

            if (hash_equals($token_data['hashed_validator'], $hashed_validator_from_cookie)) {
                $user_sql = "SELECT id, email, username FROM User WHERE id = ?";
                $user_stmt = $conn->prepare($user_sql);
                $user_stmt->bind_param('i', $token_data['user_id']);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user = $user_result->fetch_assoc();

                // session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
            }
        }
    }
}
