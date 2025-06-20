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
            <form action="scripts/add_address.php" method="POST">

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Adress line 1 *</p>
                        <input type="text" name="adress_1" placeholder="Street Name and Number" required
                            value="<?php echo htmlspecialchars($old_input['adress_1'] ?? ''); ?>" />
                        <?php if (isset($errors['adress_1'])): ?>
                            <p class="error-message"><?php echo $errors['adress_1']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <p class="text"> Adress line 2 </p>
                        <input type="text" name="adress_2" placeholder="Block and apartment number"
                            value="<?php echo htmlspecialchars($old_input['adress_2'] ?? ''); ?>" />
                        <?php if (isset($errors['adress_2'])): ?>
                            <p class="error-message"><?php echo $errors['adress_2']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Postcode *</p>
                        <input type="text" name="postcode" placeholder="Postcode" required
                            value="<?php echo htmlspecialchars($old_input['postcode'] ?? ''); ?>" />
                        <?php if (isset($errors['postcode'])): ?>
                            <p class="error-message"><?php echo $errors['postcode']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <p class="text"> City *</p>
                        <input type="text" name="city" placeholder="Town/City" required
                            value="<?php echo htmlspecialchars($old_input['city'] ?? ''); ?>" />
                        <?php if (isset($errors['city'])): ?>
                            <p class="error-message"><?php echo $errors['city']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="field">
                        <p class="text"> Phone Number *</p>
                        <input type="tel" name="phone" placeholder="Phone Number" required
                            value="<?php echo htmlspecialchars($old_input['phone'] ?? ''); ?>" />
                        <?php if (isset($errors['phone'])): ?>
                            <p class="error-message"><?php echo $errors['phone']; ?></p>
                        <?php endif; ?>
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