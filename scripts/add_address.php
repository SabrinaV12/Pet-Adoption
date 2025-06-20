<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];
    $clean_data = [];

    if (!empty($_POST['adress_1'])) {
        $clean_data['adress_1'] = trim($_POST['adress_1']);
    } else {
        $errors['adress_1'] = 'Address line 1 is required.';
    }

    if (!empty($_POST['postcode'])) {
        $clean_data['postcode'] = trim($_POST['postcode']);
    } else {
        $errors['postcode'] = 'Postcode is required.';
    }

    if (!empty($_POST['city'])) {
        $clean_data['city'] = trim($_POST['city']);
    } else {
        $errors['city'] = 'City is required.';
    }

    if (!empty($_POST['phone']) && preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
        $clean_data['phone'] = $_POST['phone'];
    } else {
        $errors['phone'] = 'A valid 10-digit phone number is required.';
    }

    $clean_data['adress_2'] = isset($_POST['adress_2']) ? trim($_POST['adress_2']) : '';

    if (empty($errors)) {
        $_SESSION['adoption']['address'] = $clean_data;
        header('Location: ../adoptionHome.php');
        exit();
    } else {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ../adoptionAdress.php');
        exit();
    }
} else { //if the access is forbidden
    header('Location: ../adoptionStart.php');
    exit();
}
