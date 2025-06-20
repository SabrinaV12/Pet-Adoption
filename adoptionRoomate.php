<?php
session_start();
require_once 'database/check_auth.php';

$_SESSION['adoption']['home'] = $_POST;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomates</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionRoomate.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="add-roomate">
        <p class="important">Please note, all these details must be completed in order to apply for pet adoption.</p>

        <section class="roomate">
            <form action="adoptionOtherAnimals.php" method="POST">

                <div class="info-row">
                    <div class="field-column">
                        <label for="adults-number">Number of adults (minimum 1) *</label>
                        <input type="number"
                            id="adults-number"
                            name="item_quantity"
                            min="1"
                            step="1"
                            placeholder="Ex: 1"
                            required>
                    </div>

                    <div class="field-column">
                        <label for="childrens-number">Number of children *</label>
                        <input type="number"
                            id="childrens-number"
                            name="item_quantity"
                            min="0"
                            step="1"
                            placeholder="Ex: 0"
                            required>
                    </div>

                    <div class="field-column">
                        <label for="age-home" class="text">Age of youngest children</label>
                        <select name="level" id="age-home">

                            <option value="" disabled selected>Please Select</option>

                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>

                        </select>
                    </div>
                </div>

                <div class="field">
                    <p class="text">Any visiting children? *</p>
                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYesVisiting" name="choice_1" value="yes" required>
                            <label for="choiceYesVisiting">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNoVisiting" name="choice_1" value="no">
                            <label for="choiceNoVisiting">No</label>
                        </div>

                    </div>
                </div>

                <div class="field">
                    <label for="age-visiting" class="text">General age of visiting children</label>
                    <select name="level" id="age-visiting">

                        <option value="" disabled selected>Please Select</option>

                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>

                    </select>
                </div>

                <div class="field">
                    <p class="text">Do you have any flatmates or lodgers? *</p>
                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYesRoomate" name="choice_2" value="yes" required>
                            <label for="choiceYesRoomate">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNoRoomate" name="choice_2" value="no">
                            <label for="choiceNoRoomate">No</label>
                        </div>

                    </div>
                </div>

                <div class="buttons">
                    <a href="adoptionHome.php" class="back-button">
                        <span>Back</span>
                    </a>
                    <button type="submit" class="continue-button">Continue</button>
                </div>

            </form>

        </section>

        <?php include 'components/footer.php'; ?>
</body>

</html>