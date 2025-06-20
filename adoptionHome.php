<?php
session_start();
require_once 'database/check_auth.php';

$_SESSION['adoption']['adress'] = $_POST;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Home</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionHome.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="add-house">
        <p class="important">Please note, all these details must be completed in order to apply for pet adoption.</p>

        <section class="house">
            <form action="adoptionRoomate.php" method="POST">

                <div class="field">
                    <p class="text">Do you have a garden? *</p>

                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYes" name="choice" value="yes" required>
                            <label for="choiceYes">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNo" name="choice" value="no">
                            <label for="choiceNo">No</label>
                        </div>

                    </div>
                </div>

                <div class="field">
                    <p class="text"> Please describe your living/home situation *</p>
                    <input type="text" name="situation" placeholder="Please describe" required />
                </div>

                <div class="field">
                    <p class="text"> Can you describe your household setting *</p>
                    <input type="text" name="setting" placeholder="Please describe" required />
                </div>

                <div class="field">
                    <label for="activity-level" class="text">Can you describe the household's typical activity level? *</label>
                    <select name="level" id="activity-level" required>

                        <option value="" disabled selected>Choose a level from 1 to 10</option>

                        <option value="1">1 - Very Low</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5 - Moderate</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10 - Very High</option>

                    </select>
                </div>

                <div class="buttons">
                    <a href="adoptionAdress.php" class="back-button">
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