<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];
    $clean_data = [];

    $allowed_choices = ['yes', 'no'];
    if (!empty($_POST['has_garden']) && in_array($_POST['has_garden'], $allowed_choices)) {
        $clean_data['has_garden'] = ($_POST['has_garden'] === 'yes') ? 'Yes' : 'No';
    } else {
        $errors['has_garden'] = 'Please select an option for the garden.';
    }

    if (!empty($_POST['situation'])) {
        $clean_data['situation'] = trim(htmlspecialchars($_POST['situation']));
    } else {
        $errors['situation'] = 'Please describe your living situation.';
    }

    if (!empty($_POST['setting'])) {
        $clean_data['setting'] = trim(htmlspecialchars($_POST['setting']));
    } else {
        $errors['setting'] = 'Please describe your household setting.';
    }

    if (!empty($_POST['level']) && filter_var($_POST['level'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]])) {
        $clean_data['level'] = (int)$_POST['level'];
    } else {
        $errors['level'] = 'Please select a valid activity level from 1 to 10.';
    }

    if (empty($errors)) {
        $_SESSION['adoption']['home'] = $clean_data;
        header('Location: ../adoptionRoomate.php');
        exit();
    } else {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ../adoptionHome.php');
        exit();
    }
} else { //if the access is forbidden
    header('Location: ../adoptionStart.php');
    exit();
}
