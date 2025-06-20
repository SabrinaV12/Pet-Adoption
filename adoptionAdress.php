<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Adress</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
    <link rel="stylesheet" href="design/adoptionAdress.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="add-adress">
        <p class="important">Please note, all these details must be completed in order to apply for pet adoption.</p>

        <section class="address">
            <form action="adoptionHome.php" method="POST">

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Adress line 1 *</p>
                        <input type="text" name="adress_1" placeholder="Street Name and Number" required />
                    </div>

                    <div class="field">
                        <p class="text"> Adress line 2 </p>
                        <input type="text" name="adress_2" placeholder="Block and apartment number" />
                    </div>
                </div>

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Postcode *</p>
                        <input type="text" name="postcode" placeholder="Postcode" required />
                    </div>

                    <div class="field">
                        <p class="text"> City *</p>
                        <input type="text" name="city" placeholder="Town/City" required />
                    </div>
                </div>

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Phone Number *</p>
                        <input type="tel" name="phone" placeholder="Phone Number" required />
                    </div>
                </div>

                <div class="buttons">
                    <a href="adoptionStart.php" class="back-button">
                        <span>Back</span>
                    </a>
                    <button type="submit" class="continue-button">Continue</button>
                </div>
            </form>

        </section>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>