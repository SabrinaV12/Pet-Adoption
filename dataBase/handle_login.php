<?php

session_start();

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, username, hash_password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['hash_password'])) {

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $selector = bin2hex(random_bytes(12));
            $validator = bin2hex(random_bytes(32));

            $expires = new DateTime('+30 days');
            $expires_db_format = $expires->format('Y-m-d H:i:s');

            $hashed_validator = hash('sha256', $validator);

            $sql_insert_token = "INSERT INTO auth_tokens (user_id, selector, hashed_validator, expires) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_token);
            $stmt_insert->bind_param("isss", $user['id'], $selector, $hashed_validator, $expires_db_format);
            $stmt_insert->execute();

            $cookie_value = $selector . ':' . $validator;
            setcookie(
                'remember_me_token',
                $cookie_value,
                [
                    'expires' => $expires->getTimestamp(),
                    'path' => '/',
                    'domain' => '',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );

            header("location: ../searchMenu.php");
            exit;
        } else {
            header("location: ../login.php?error=invalid_credentials");
            exit;
        }
    } else {
        header("location: ../login.php?error=invalid_credentials");
        // exit;
    }

    $stmt->close();
    $conn->close();
}
