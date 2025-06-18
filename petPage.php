<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hosha's Page</title>
    <link rel="stylesheet" href="design/petPage.css" />
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">

    <?php include 'components/header.php'; ?>

    <section class="page">
        <div class="banner_wrapper">
            <img src="assets/banner.png" alt="Banner" class="banner" />
            <img src="assets/profile_picture.png" alt="ProfilePicture" class="profile_picture" />
        </div>

        <section class="pet_details">

            <div class="main_content">
                <h1 class="big_text">Magie</h1>
                <p class="small_text">Pet ID: 80638810</p>
                <a href="adoptionStart.php" class="adopt_button">
                    <span>Adopt</span>
                    <img src="assets/arrow_circle_right.png" alt="Adopt" />
                </a>
            </div>

            <div class="location_info">
                <img src="assets/USA_flag.png" alt="USA Flag" class="location_info_flag" />

                <div class="location_text_block">
                    <div class="location_line">
                        <span class="small_text">North America</span>
                    </div>
                    <div class="location_line">
                        <img src="assets/location_ping.png" alt="Location pin" class="location_pin_icon" />
                        <span class="small_text">California</span>
                    </div>
                </div>
            </div>

        </section>
    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>