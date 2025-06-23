<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create a new account</title>
  <link rel="stylesheet" href="design/header.css" />
  <link rel="stylesheet" href="design/footer.css" />
  <link rel="stylesheet" href="design/register.css" />
</head>

<body>
<?php include 'components/header.php'; ?>

<section class="register">
  <form action="database/handle_register.php" method="POST" enctype="multipart/form-data">
    <?php if (isset($_GET['error'])): ?>
      <p class="error-message">
        <?php
        if ($_GET['error'] == 'password_mismatch') echo "The passwords don't match!";
        if ($_GET['error'] == 'email_exists') echo 'This email is already used!';
        if ($_GET['error'] == 'username_exists') echo 'This username is already used!';
        ?>
      </p>
    <?php endif; ?>

    <p class="text"> First Name </p>
    <input type="text" name="first_name" placeholder="Enter your first name" required />

    <p class="text"> Last Name </p>
    <input type="text" name="last_name" placeholder="Enter your last name" required />

    <p class="text"> Username </p>
    <input type="text" name="username" placeholder="Enter your username" required />

    <p class="text"> Email </p>
    <input type="text" name="email" placeholder="Enter your email address" required />

    <p class="text"> Phone Number </p>
    <input type="tel" name="phone" placeholder="Enter your phone number" required />

    <p class="text"> Bio </p>
<textarea name="description" placeholder="Tell us something about yourself" rows="5" required></textarea>

    <p class="text"> Country </p>
    <input type="text" name="country" placeholder="Enter your country" required />

    <p class="text"> County </p>
    <input type="text" name="county" placeholder="Enter your county" required />

    <p class="text"> Telegram Handle </p>
    <input type="text" name="telegram_handle" placeholder="Enter your Telegram @handle" required />

    <p class="text"> Profile Picture </p>
    <input type="file" name="profile_picture" accept="image/*" required />

    <p class="text"> Banner Picture </p>
    <input type="file" name="banner_picture" accept="image/*" required />

    <p class="text"> Password </p>
    <input type="password" name="password" placeholder="Enter your password" required />

    <p class="text"> Confirm Password </p>
    <input type="password" name="confirm_password" placeholder="Reenter your password" required />

    <button type="submit">Create a new Account</button>
  </form>

  <div class="help-buttons">
    <a href="login.php">Already have an account?</a>
  </div>
</section>

<?php include 'components/footer.php'; ?>
</body>
</html>
