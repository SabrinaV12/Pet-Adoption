<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
    <link rel="stylesheet" href="design/login.css" />
</head>
<body>
    <?php include 'components/header.php'; ?>

    <section class="login">
        <form action="home.php" method="POST">

            <p class="text"> Username </p>
            <input type="text" name="username" placeholder="Enter your username" required />

            <p class="text"> Password </p>
            <input type="password" name="password" placeholder="Enter your password" required />

            <button type="submit">Login</button>
        </form>

        <div class="help-buttons">
        <a href="forgot.php" class="text">Forgot your password?</a>
        <a href="register.php">Don't have an account?</a>
        </div>

    </section>

    <?php include 'components/footer.php'; ?>
</body>
</html>