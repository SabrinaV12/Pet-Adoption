<?php
session_start();
require_once 'database/check_auth.php';
require_once 'database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name, username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionStart.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">


    <?php include 'components/header.php'; ?>

    <section class="user-information">
        <div class="profile-card">

            <div class="image-card">
                <img src="assets/profile_head.png" alt="Poza de profil">
            </div>

            <div class="info-card">
                <div class="info-row">
                    <h1>Username</h1>
                    <p><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <div class="info-row">
                    <h1>First name</h1>
                    <p><?php echo htmlspecialchars($user['first_name']); ?></p>
                </div>
                <div class="info-row">
                    <h1>Last name</h1>
                    <p><?php echo htmlspecialchars($user['last_name']); ?></p>
                </div>
                <div class="info-row">
                    <h1>Email</h1>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

        </div>

        <a href="adoptionAdress.php" class="start-button">
            <span>Start</span>
        </a>

    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>