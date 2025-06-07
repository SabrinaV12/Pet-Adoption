<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a new account</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
    <link rel="stylesheet" href="design/register.css" />
</head>
<body>
    <?php include 'components/header.php'; ?>

    <section class="register">
        <form action="home.php" method="POST">

            <p class="text"> First Name </p>
            <input type="text" name="first_name" placeholder="Enter your first name" required />

            <p class="text"> Last Name </p>
            <input type="text" name="last_name" placeholder="Enter your last name" required />

            <p class="text"> Username </p>
            <input type="text" name="username" placeholder="Enter your username" required />

            <p class="text"> Email </p>
            <input type="text" name="mail" placeholder="Enter your email address" required />

            <p class="text"> Phone Number </p>
            <input type="tel" name="phone" placeholder="Enter your phone number" required />

            <p class="text"> Password </p>
            <input type="password" name="password" placeholder="Enter your password" required />

             <p class="text"> Confirm Password </p>
            <input type="password" name="password" placeholder="Reenter your password" required />

            <button type="submit">Create a new Account</button>
        </form>

        <div class="help-buttons">
        <a href="login.php">Already have an account?</a>
        </div>

    </section>

    <?php include 'components/footer.php'; ?>
</body>
</html>