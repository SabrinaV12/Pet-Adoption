<?php

session_start();

if (!isset($_SESSION['adoption']['pet_id'])) {
    header('Location: ../adoptionStart.php?error=session_expired_or_invalid');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];
    $clean_data = [];

    $num_adults_validated = filter_var($_POST['num_adults'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($num_adults_validated !== false) {
        $clean_data['num_adults'] = $num_adults_validated;
    } else {
        $errors['num_adults'] = 'Please enter a valid number of adults (minimum 1).';
    }

    $num_children_validated = filter_var($_POST['num_children'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($num_children_validated !== false) {
        $clean_data['num_children'] = $num_children_validated;
    } else {
        $errors['num_children'] = 'Please enter a valid number of children (minimum 0).';
    }

    if (($clean_data['num_children'] ?? 0) > 0) {
        if (!empty($_POST['youngest_age']) && filter_var($_POST['youngest_age'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 18]])) {
            $clean_data['youngest_age'] = (int)$_POST['youngest_age'];
        } else {
            $errors['youngest_age'] = 'Please select the age of the youngest child.';
        }
    } else {
        $clean_data['youngest_age'] = null;
    }

    $allowed_choices_visiting = ['yes', 'no'];
    if (!empty($_POST['visiting_children']) && in_array($_POST['visiting_children'], $allowed_choices_visiting)) {
        $clean_data['visiting_children'] = ($_POST['visiting_children'] === 'yes') ? 'Yes' : 'No';
    } else {
        $errors['visiting_children'] = 'Please select an option for visiting children.';
    }

    if (($clean_data['visiting_children'] ?? '') === 'Yes') {
        if (!empty($_POST['visiting_ages']) && filter_var($_POST['visiting_ages'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 18]])) {
            $clean_data['visiting_ages'] = (int)$_POST['visiting_ages'];
        } else {
            $errors['visiting_ages'] = 'Please select the general age of visiting children.';
        }
    } else {
        $clean_data['visiting_ages'] = null;
    }

    $allowed_choices_flatmates = ['yes', 'no'];
    if (!empty($_POST['has_flatmates']) && in_array($_POST['has_flatmates'], $allowed_choices_flatmates)) {
        $clean_data['has_flatmates'] = ($_POST['has_flatmates'] === 'yes') ? 'Yes' : 'No';
    } else {
        $errors['has_flatmates'] = 'Please select an option for flatmates.';
    }

    if (empty($errors)) {
        $_SESSION['adoption']['roommate'] = $clean_data;
        header('Location: ../adoptionOtherAnimals.php');
        exit();
    } else {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ../adoptionRoomate.php');
        exit();
    }
} else { //if the access is forbidden
    header('Location: ../adoptionStart.php');
    exit();
}
