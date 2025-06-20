<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finished!</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionConfirm.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="confirmation">

        <h1>Thanks For Submitting</h1>
        <p>The pet's current owner will be sent a link to your profile when your application has been approved by Furry Friends.</p>
        <p>You can go back to your profile or download your adoption request.</p>

        <a href="humanPage.php" class="profile-button">
            <span>Go To My Profile</span>
        </a>

        <a href="home.php" class="download-button">
            <span>Download the Adoption Request</span>
        </a>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>