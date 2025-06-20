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
            <form action="scripts/add_roomate.php" method="POST">

                <div class="info-row">
                    <div class="field-column">
                        <label for="adults-number">Number of adults (minimum 1) *</label>
                        <input type="number" id="adults-number" name="num_adults" min="1" step="1" placeholder="Ex: 1" required
                            value="<?php echo htmlspecialchars($old_input['num_adults'] ?? '1'); ?>">
                        <?php if (isset($errors['num_adults'])): ?><p class="error-message"><?php echo $errors['num_adults']; ?></p><?php endif; ?>
                    </div>

                    <div class="field-column">
                        <label for="childrens-number">Number of children *</label>
                        <input type="number" id="childrens-number" name="num_children" min="0" step="1" placeholder="Ex: 0" required
                            value="<?php echo htmlspecialchars($old_input['num_children'] ?? '0'); ?>">
                        <?php if (isset($errors['num_children'])): ?><p class="error-message"><?php echo $errors['num_children']; ?></p><?php endif; ?>
                    </div>

                    <div class="field-column">
                        <label for="age-home" class="text">Age of youngest children</label>
                        <select name="youngest_age" id="age-home">
                            <option value="">Please Select</option>
                            <?php for ($i = 1; $i <= 18; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php if (($old_input['youngest_age'] ?? '') == $i) echo 'selected'; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <?php if (isset($errors['youngest_age'])): ?><p class="error-message"><?php echo $errors['youngest_age']; ?></p><?php endif; ?>
                    </div>
                </div>

                <div class="field">
                    <p class="text">Any visiting children? *</p>
                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYesVisiting" name="visiting_children" value="yes" required
                                <?php if (($old_input['visiting_children'] ?? '') === 'yes') echo 'checked'; ?>>
                            <label for="choiceYesVisiting">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNoVisiting" name="visiting_children" value="no"
                                <?php if (($old_input['visiting_children'] ?? '') === 'no') echo 'checked'; ?>>
                            <label for="choiceNoVisiting">No</label>
                        </div>
                    </div>
                    <?php if (isset($errors['visiting_children'])): ?><p class="error-message"><?php echo $errors['visiting_children']; ?></p><?php endif; ?>
                </div>

                <div class="field">
                    <label for="age-visiting" class="text">General age of visiting children</label>
                    <select name="visiting_ages" id="age-visiting">
                        <option value="">Please Select</option>
                        <?php for ($i = 1; $i <= 18; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if (($old_input['visiting_ages'] ?? '') == $i) echo 'selected'; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($errors['visiting_ages'])): ?><p class="error-message"><?php echo $errors['visiting_ages']; ?></p><?php endif; ?>
                </div>

                <div class="field">
                    <p class="text">Do you have any flatmates or lodgers? *</p>
                    <div class="choice">

                        <div class="option">
                            <input type="radio" id="choiceYesRoomate" name="has_flatmates" value="yes" required
                                <?php if (($old_input['has_flatmates'] ?? '') === 'yes') echo 'checked'; ?>>
                            <label for="choiceYesRoomate">Yes</label>
                        </div>

                        <div class="option">
                            <input type="radio" id="choiceNoRoomate" name="has_flatmates" value="no"
                                <?php if (($old_input['has_flatmates'] ?? '') === 'no') echo 'checked'; ?>>
                            <label for="choiceNoRoomate">No</label>
                        </div>

                    </div>
                    <?php if (isset($errors['has_flatmates'])): ?><p class="error-message"><?php echo $errors['has_flatmates']; ?></p><?php endif; ?>
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