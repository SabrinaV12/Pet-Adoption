<?php
session_start();
require_once 'database/check_auth.php';

$_SESSION['adoption']['roomate'] = $_POST;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other Animals</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionOtherAnimals.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="animal">

        <p class="important">Please note, all these details must be completed in order to apply for pet adoption.</p>

        <form action="adoptionConfirm.php" method="POST">

            <div class="field">
                <p class="text"> Does anyone in the household have any allergies to pets? *</p>
                <input type="text" name="allergies" placeholder="Please describe" required />
            </div>

            <div class="field">
                <p class="text">Are there any other animals at your home? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesAnimal" name="choice_1" value="yes" required>
                        <label for="choiceYesAnimal">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNoAnimal" name="choice_1" value="no">
                        <label for="choiceNoAnimal">No</label>
                    </div>
                </div>
            </div>

            <div class="field">
                <p class="text"> If yes, please state their species, age and gender</p>
                <input type="text" name="situation" placeholder="Please describe" />
            </div>

            <div class="field">
                <p class="text">If yes, are they neutered? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesNeutered" name="choice_2" value="yes" required>
                        <label for="choiceYesNeutered">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNo" name="choice_2" value="no">
                        <label for="choiceNoNeutered">No</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNotApplicableNeutered" name="choice_2" value="not_applicable">
                        <label for="choiceNotApplicableNeutered">Not Applicable</label>
                    </div>

                </div>
            </div>

            <div class="field">
                <p class="text">If yes, have they been vaccinated in the last 12 months? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesVaccinated" name="choice_3" value="yes" required>
                        <label for="choiceYesVaccinated">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNoVaccinated" name="choice_3" value="no">
                        <label for="choiceNoVaccinated">No</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNotApplicableVaccinated" name="choice_3" value="not_applicable">
                        <label for="choiceNotApplicableVaccinated">Not Applicable</label>
                    </div>

                </div>
            </div>

            <div class="field">
                <p class="text"> Please describe your experience of any previous pet ownership and tell us about the type of home you plan to offer your new pet </p>
                <input type="text" name="situation" placeholder="Please describe" />
            </div>

            <div class="buttons">
                <a href="adoptionRoomate.php" class="back-button">
                    <span>Back</span>
                </a>
                <button type="submit" class="continue-button">Continue</button>
            </div>
        </form>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>