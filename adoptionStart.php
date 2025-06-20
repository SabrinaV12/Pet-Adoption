<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionStart.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">


    <?php include 'components/header.php'; ?>

    <section class="user-information">
        <div class="profile-card">

            <div class="image-card">
                <img src="assets/profile_head.png" alt="Poza de profil">
            </div>

            <div class="info-card">
                <div class="info-row">
                    <h1>Username</h1>
                    <p>User</p>
                </div>
                <div class="info-row">
                    <h1>First name</h1>
                    <p>John</p>
                </div>
                <div class="info-row">
                    <h1>Last name</h1>
                    <p>Doe</p>
                </div>
                <div class="info-row">
                    <h1>Email</h1>
                    <p>johndoe@email.com</p>
                </div>
            </div>

        </div>

        <a href="adoptionAdress.php" class="start-button">
            <span>Start</span>
        </a>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>