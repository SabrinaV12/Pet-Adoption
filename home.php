<?php
session_start();
require_once 'database/check_auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<body>
    <?php include 'components/header.php'; ?>

    <?php include 'components/footer.php'; ?>
</body>

</html>