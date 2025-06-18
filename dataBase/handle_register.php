<?php
require_once 'initDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        header("location: ../register.php?error=password_mismatch");
        exit();
    }

    $sql_check = "SELECT id FROM User WHERE email = ? OR username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $email, $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("location: ../register.php?error=email_exists");
        exit();
    }
    $stmt_check->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO User (username, email, password) VALUES (?, ?, ?)"; //!!!to add after the full DB: first_name, last_name, phone!!!
    $stmt_insert = $conn->prepare($sql_insert);

    if ($stmt_insert === false) {
        die("Eroare la pregatirea interogarii de inserare: " . $conn->error);
    }

    $stmt_insert->bind_param("sss", $username, $email, $hashedPassword); //!!!to add after the full DB: $firstName, $lastName, $phone!!!

    if ($stmt_insert->execute()) {
        header("location: ../login.php?success=registered");
        exit();
    } else {
        die("Ceva nu a functionat. Va rugam incercati din nou.");
    }

    $stmt_insert->close();
    $conn->close();
} else {
    header("location: ../home.php");
    exit();
}
