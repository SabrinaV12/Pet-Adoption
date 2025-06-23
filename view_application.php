<?php
session_start();
require_once 'database/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$application_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];

$sql = "SELECT a.*, p.name as pet_name FROM applications a JOIN pets p ON a.pet_id = p.id WHERE a.id = ? AND p.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $application_id, $current_user_id);
$stmt->execute();
$application = $stmt->get_result()->fetch_assoc();

if (!$application) {
    die("Application not found or access denied.");
}

echo "<h1>Application for " . htmlspecialchars($application['pet_name']) . "</h1>";

echo '<a href="scripts/download_form.php?id=' . $application_id . '&format=csv">Download as CSV</a>';
echo '<a href="scripts/download_form.php?id=' . $application_id . '&format=json">Download as JSON</a>';

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
