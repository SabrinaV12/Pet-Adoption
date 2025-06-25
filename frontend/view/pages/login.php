<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
    <link rel="stylesheet" href="design/login.css" />
</head>

<body>
    <?php include 'components/header.php'; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
        <p class="success-message">Account was successfuly created! You can now login.</p>
    <?php endif; ?>

    <section class="login">
        <form action="database/handle_login.php" method="POST">

            <?php if (isset($_GET['error'])): ?>
                <p class="error-message">Username or Password are incorrect!</p>
            <?php endif; ?>

            <p class="text"> Username </p>
            <input type="text" name="username" placeholder="Enter your username" required />

            <p class="text"> Password </p>
            <input type="password" name="password" placeholder="Enter your password" required />

            <button type="submit">Login</button>
        </form>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>