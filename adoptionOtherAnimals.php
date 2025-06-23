<?php
session_start();
require_once 'database/check_auth.php';

if (!isset($_SESSION['adoption']['pet_id'])) {
    header('Location: adoptionStart.php?error=session_expired_or_invalid');
    exit();
}

$errors = $_SESSION['form_errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

unset($_SESSION['form_errors']);
unset($_SESSION['old_input']);
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

        <form action="scripts/add_other_animal.php" method="POST">

            <div class="field">
                <p class="text"> Does anyone in the household have any allergies to pets? *</p>
                <input type="text" name="allergies" placeholder="Please describe" required
                    value="<?php echo htmlspecialchars($old_input['allergies'] ?? ''); ?>" />
                <?php if (isset($errors['allergies'])): ?><p class="error-message"><?php echo $errors['allergies']; ?></p><?php endif; ?>

            </div>

            <div class="field">
                <p class="text">Are there any other animals at your home? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesAnimal" name="has_animals" value="yes" required
                            <?php if (($old_input['has_animals'] ?? '') === 'yes') echo 'checked'; ?>>
                        <label for="choiceYesAnimal">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNoAnimal" name="has_animals" value="no"
                            <?php if (($old_input['has_animals'] ?? '') === 'no') echo 'checked'; ?>>
                        <label for="choiceNoAnimal">No</label>
                    </div>
                </div>
                <?php if (isset($errors['has_animals'])): ?><p class="error-message"><?php echo $errors['has_animals']; ?></p><?php endif; ?>
            </div>

            <div class="field">
                <p class="text"> If yes, please state their species, age and gender</p>
                <input type="text" name="other_animals_info" placeholder="Please describe"
                    value="<?php echo htmlspecialchars($old_input['other_animals_info'] ?? ''); ?>" />
                <?php if (isset($errors['other_animals_info'])): ?><p class="error-message"><?php echo $errors['other_animals_info']; ?></p><?php endif; ?>
            </div>

            <div class="field">
                <p class="text">If yes, are they neutered? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesNeutered" name="neutered" value="yes" required
                            <?php if (($old_input['neutered'] ?? '') === 'yes') echo 'checked'; ?>>
                        <label for="choiceYesNeutered">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNoNeutered" name="neutered" value="no"
                            <?php if (($old_input['neutered'] ?? '') === 'no') echo 'checked'; ?>>
                        <label for="choiceNoNeutered">No</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNotApplicableNeutered" name="neutered" value="not_applicable"
                            <?php if (($old_input['neutered'] ?? '') === 'not_applicable') echo 'checked'; ?>>
                        <label for="choiceNotApplicableNeutered">Not Applicable</label>
                    </div>

                </div>
            </div>

            <div class="field">
                <p class="text">If yes, have they been vaccinated in the last 12 months? *</p>
                <div class="choice">

                    <div class="option">
                        <input type="radio" id="choiceYesVaccinated" name="vaccinated" value="yes" required
                            <?php if (($old_input['vaccinated'] ?? '') === 'yes') echo 'checked'; ?>>
                        <label for="choiceYesVaccinated">Yes</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNoVaccinated" name="vaccinated" value="no"
                            <?php if (($old_input['vaccinated'] ?? '') === 'no') echo 'checked'; ?>>
                        <label for="choiceNoVaccinated">No</label>
                    </div>

                    <div class="option">
                        <input type="radio" id="choiceNotApplicableVaccinated" name="vaccinated" value="not_applicable"
                            <?php if (($old_input['vaccinated'] ?? '') === 'not_applicable') echo 'checked'; ?>>
                        <label for="choiceNotApplicableVaccinated">Not Applicable</label>
                    </div>

                </div>
                <?php if (isset($errors['vaccinated'])): ?><p class="error-message"><?php echo $errors['vaccinated']; ?></p><?php endif; ?>
            </div>

            <div class="field">
                <p class="text"> Please describe your experience of any previous pet ownership and tell us about the type of home you plan to offer your new pet </p>
                <input type="text" name="experience" placeholder="Please describe"
                    value="<?php echo htmlspecialchars($old_input['experience'] ?? ''); ?>" />
                <?php if (isset($errors['experience'])): ?><p class="error-message"><?php echo $errors['experience']; ?></p><?php endif; ?>

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