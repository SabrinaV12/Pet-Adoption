<?php
session_start();
require_once 'database/check_auth.php';
require_once 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$application_id = $_GET['app_id'] ?? 0;

//At this moment anyone who reaches the confirmation means they can download.
if ($application_id > 0) {
    $user_can_download = true;
} else {
    $user_can_download = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finished!</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/adoptionConfirm.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body class="container">
    <?php include 'components/header.php'; ?>

    <section class="confirmation">

        <h1>Thanks For Submitting</h1>
        <p>The pet's current owner will be sent a link to your profile when your application has been approved by Furry Friends.</p>
        <p>You can go back to your profile or download your adoption request.</p>

        <?php if ($user_can_download): ?>
            <div class="buttons">
                <div class="download-buttons">
                    <a href="scripts/download_form.php?id=<?php echo $application_id; ?>&format=csv" class="download-button">
                        <span>Download as Excel File</span>
                    </a>
                    <a href="scripts/download_form.php?id=<?php echo $application_id; ?>&format=json" class="download-button">
                        <span>Download as JSON File</span>
                    </a>
                </div>

                <a href="userProfile.php" class="profile-button">
                    <span>Go To My Profile</span>
                </a>
            </div>
        <?php endif; ?>
    </section>

    <?php include 'components/footer.php'; ?>
</body>

</html>