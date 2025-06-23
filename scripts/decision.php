<?php
session_start();
require_once '../database/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);
    $decision = $_POST['decision'];
    $current_user_id = $_SESSION['user_id'];

    //we verify if it's the right user
    $sql = "SELECT a.id, a.pet_id, a.applicant_id, p.user_id as owner_id, p.name as pet_name FROM applications a JOIN pets p ON a.pet_id = p.id WHERE a.id = ? AND p.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $current_user_id);
    $stmt->execute();
    $app_data = $stmt->get_result()->fetch_assoc();

    if (!$app_data || ($decision !== 'approve' && $decision !== 'deny')) {
        die("Error: Access Denied.");
    }

    $new_status = ($decision === 'approve') ? 'approved' : 'denied';

    $update_app = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $update_app->bind_param("si", $new_status, $application_id);
    $update_app->execute();

    if ($decision === 'approve') {
        $new_owner_id = $app_data['applicant_id'];

        $sql_update_pet = "UPDATE pets SET adopted = 1, user_id = ?, adoption_date = CURDATE() WHERE id = ?";

        $update_pet_stmt = $conn->prepare($sql_update_pet);

        $update_pet_stmt->bind_param("ii", $new_owner_id, $app_data['pet_id']);

        $update_pet_stmt->execute();
    }

    $applicant_id = $app_data['applicant_id'];
    $pet_name = $app_data['pet_name'];
    $decision_text = ($decision === 'approve') ? 'approved' : 'denied';
    $applicant_message = "Your request for " . htmlspecialchars($pet_name) . " was " . $decision_text . ".";
    $applicant_link = "petPage.php?id=" . $app_data['pet_id'];

    $notify_applicant = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
    $notify_applicant->bind_param("iss", $applicant_id, $applicant_message, $applicant_link);
    $notify_applicant->execute();

    header('Location: ../userProfile.php?decision=success');
    exit();
}
