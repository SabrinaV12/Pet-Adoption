<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
    <link rel="stylesheet" href="../../frontend/view/styles/view_application.css">
</head>

<body>
    <?php
    require_once __DIR__ . '/../repositories/database/db.php';
    require_once __DIR__ . '/../services/JwtService.php';

    try {
        $token = $jwtService->getToken();
        $decodedPayload = $jwtService->verifyToken($token);

        $current_user_id = $decodedPayload->data->user_id;
    } catch (Exception $e) {
        header('HTTP/1.1 401 Unauthorized');
        header('Location: login.php?error=' . urlencode('Authentication required'));
        exit();
    }

    $application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT a.*, p.name as pet_name FROM applications a JOIN pets p ON a.pet_id = p.id WHERE a.id = ? AND p.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $current_user_id);
    $stmt->execute();
    $application = $stmt->get_result()->fetch_assoc();

    if (!$application) {
        die("Application not found or access denied.");
    }

    echo "<h1>Application for " . htmlspecialchars($application['pet_name']) . "</h1>";

    echo '<div class="download-buttons">';
    echo '<a href="scripts/download_form.php?id=' . $application_id . '&format=csv">Download as CSV</a>';
    echo '<a href="scripts/download_form.php?id=' . $application_id . '&format=json">Download as JSON</a>';
    echo '</div>';

    if ($application['status'] === 'pending') {
    ?>
        <form action="scripts/decision.php" method="POST">
            <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
            <button type="submit" name="decision" value="approve">Aprove</button>
            <button type="submit" name="decision" value="deny">Deny</button>
        </form>
    <?php
    } else {
        echo "<h3>Final decision: " . ucfirst($application['status']) . "</h3>";
    }
