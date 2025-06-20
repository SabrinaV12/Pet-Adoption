<?php
session_start();
require_once '../database/db.php';

//First we validate the last page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];
    $clean_data_step4 = [];

    if (!empty($_POST['allergies'])) {
        $clean_data_step4['allergies'] = trim(htmlspecialchars($_POST['allergies']));
    } else {
        $errors['allergies'] = 'Please provide information about allergies.';
    }

    $allowed_animals = ['yes', 'no'];
    if (!empty($_POST['has_animals']) && in_array($_POST['has_animals'], $allowed_animals)) {
        $clean_data_step4['has_animals'] = ($_POST['has_animals'] === 'yes') ? 'Yes' : 'No';
    } else {
        $errors['has_animals'] = 'Please select if you have other animals.';
    }

    if (($clean_data_step4['has_animals'] ?? '') === 'Yes') {
        if (!empty($_POST['other_animals_info'])) {
            $clean_data_step4['other_animals_info'] = trim(htmlspecialchars($_POST['other_animals_info']));
        } else {
            $errors['other_animals_info'] = 'Please describe your other animals.';
        }
    } else {
        $clean_data_step4['other_animals_info'] = null;
    }

    $allowed_choices_na = ['yes', 'no', 'not_applicable'];
    if (!empty($_POST['neutered']) && in_array($_POST['neutered'], $allowed_choices_na)) {
        $clean_data_step4['neutered'] = ucfirst(str_replace('_', ' ', $_POST['neutered']));
    } else {
        $errors['neutered'] = 'Please select the neutered status.';
    }

    if (!empty($_POST['vaccinated']) && in_array($_POST['vaccinated'], $allowed_choices_na)) {
        $clean_data_step4['vaccinated'] = ucfirst(str_replace('_', ' ', $_POST['vaccinated']));
    } else {
        $errors['vaccinated'] = 'Please select the vaccination status.';
    }

    $clean_data_step4['experience'] = isset($_POST['experience']) ? trim(htmlspecialchars($_POST['experience'])) : '';

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ../adoptionOtherAnimals.php');
        exit();
    }

    // Now we insert into the table all the informations
    if (!isset($_SESSION['adoption']['address']) || !isset($_SESSION['adoption']['home']) || !isset($_SESSION['adoption']['roommate'])) {
        header('Location: ../adoptionStart.php?error=session_expired');
        exit();
    }

    $address_data = $_SESSION['adoption']['address'];
    $home_data = $_SESSION['adoption']['home'];
    $roommate_data = $_SESSION['adoption']['roommate'];

    $sql = "INSERT INTO applications (
                address_line1, address_line2, postcode, city, phone_number,
                has_garden, living_situation, household_setting, activity_level,
                num_adults, num_children, youngest_child_age, visiting_children, visiting_children_ages,
                has_flatmates, has_allergies, has_other_animals, other_animals_info,
                neutered, vaccinated, experience
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssssssiiisisiisssss",
        $address_data['adress_1'],
        $address_data['adress_2'],
        $address_data['postcode'],
        $address_data['city'],
        $address_data['phone'],
        $home_data['has_garden'],
        $home_data['situation'],
        $home_data['setting'],
        $home_data['level'],
        $roommate_data['num_adults'],
        $roommate_data['num_children'],
        $roommate_data['youngest_age'],
        $roommate_data['visiting_children'],
        $roommate_data['visiting_ages'],
        $roommate_data['has_flatmates'],
        $clean_data_step4['allergies'],
        $clean_data_step4['has_animals'],
        $clean_data_step4['other_animals_info'],
        $clean_data_step4['neutered'],
        $clean_data_step4['vaccinated'],
        $clean_data_step4['experience']
    );

    if ($stmt->execute()) {
        $new_application_id = $conn->insert_id;
        unset($_SESSION['adoption']);
        unset($_SESSION['form_errors']);
        unset($_SESSION['old_input']);

        header('Location: ../adoptionConfirm.php?app_id=' . $new_application_id);
        exit();
    } else {
        header('Location: ../adoptionStart.php?error=submission_failed');
        exit();
    }
} else { //if the access is forbidden
    header('Location: ../adoptionStart.php');
    exit();
}
