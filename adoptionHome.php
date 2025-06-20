<?php
session_start();
require_once 'database/check_auth.php';

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
            <form action="scripts/add_home.php" method="POST">

                <div class="field">
                    <p class="text">Do you have a garden? *</p>

                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYes" name="has_garden" value="yes" required
                                <?php if (($old_input['has_garden'] ?? '') === 'yes') echo 'checked'; ?>>
                            <label for="choiceYes">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNo" name="has_garden" value="no"
                                <?php if (($old_input['has_garden'] ?? '') === 'no') echo 'checked'; ?>>
                            <label for="choiceNo">No</label>
                        </div>

                    </div>
                    <?php if (isset($errors['has_garden'])): ?>
                        <p class="error-message"><?php echo $errors['has_garden']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <p class="text"> Please describe your living/home situation *</p>
                    <input type="text" name="situation" placeholder="Please describe" required
                        value="<?php echo htmlspecialchars($old_input['situation'] ?? ''); ?>" />
                    <?php if (isset($errors['situation'])): ?>
                        <p class="error-message"><?php echo $errors['situation']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <p class="text"> Can you describe your household setting *</p>
                    <input type="text" name="setting" placeholder="Please describe" required
                        value="<?php echo htmlspecialchars($old_input['setting'] ?? ''); ?>" />
                    <?php if (isset($errors['setting'])): ?>
                        <p class="error-message"><?php echo $errors['setting']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="activity-level" class="text">Can you describe the household's typical activity level? *</label>
                    <select name="level" id="activity-level" required>

                        <option value="" disabled <?php if (empty($old_input['level'])) echo 'selected'; ?>>Choose a level from 1 to 10</option>

                        <option value="1" <?php if (($old_input['level'] ?? '') == '1') echo 'selected'; ?>>1 - Very Low</option>
                        <option value="2" <?php if (($old_input['level'] ?? '') == '2') echo 'selected'; ?>>2</option>
                        <option value="3" <?php if (($old_input['level'] ?? '') == '3') echo 'selected'; ?>>3</option>
                        <option value="4" <?php if (($old_input['level'] ?? '') == '4') echo 'selected'; ?>>4</option>
                        <option value="5" <?php if (($old_input['level'] ?? '') == '5') echo 'selected'; ?>>5 - Moderate</option>
                        <option value="6" <?php if (($old_input['level'] ?? '') == '6') echo 'selected'; ?>>6</option>
                        <option value="7" <?php if (($old_input['level'] ?? '') == '7') echo 'selected'; ?>>7</option>
                        <option value="8" <?php if (($old_input['level'] ?? '') == '8') echo 'selected'; ?>>8</option>
                        <option value="9" <?php if (($old_input['level'] ?? '') == '9') echo 'selected'; ?>>9</option>
                        <option value="10" <?php if (($old_input['level'] ?? '') == '10') echo 'selected'; ?>>10 - Very High</option>

                    </select>
                    <?php if (isset($errors['level'])): ?>
                        <p class="error-message"><?php echo $errors['level']; ?></p>
                    <?php endif; ?>
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