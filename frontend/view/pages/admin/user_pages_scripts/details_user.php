<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('location: ../../login.php');
    exit;
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: ../adminUsers.php?error=invalid_id');
    exit;
}
$user_id = $_GET['id'];

$sql = "SELECT id, first_name, last_name, username, email, phone_number, role, description FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: ../adminUsers.php?error=not_found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details for: <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="../../design/details_user.css">
</head>

<body>
    <header>
        <h1>Details for User: <?php echo htmlspecialchars($user['username']); ?></h1>
        <nav>
            <a href="../adminUsers.php">Back to Users List</a>
            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit-button">Edit This User</a>
        </nav>
    </header>

    <main class="user-details-container">
        <div class="user-info">
            <h2>User Information</h2>
            <dl>
                <dt>User ID</dt>
                <dd><?php echo $user['id']; ?></dd>

                <dt>First Name</dt>
                <dd><?php echo htmlspecialchars($user['first_name']); ?></dd>

                <dt>Last Name</dt>
                <dd><?php echo htmlspecialchars($user['last_name']); ?></dd>

                <dt>Username</dt>
                <dd><?php echo htmlspecialchars($user['username']); ?></dd>

                <dt>Email</dt>
                <dd><?php echo htmlspecialchars($user['email']); ?></dd>

                <dt>Phone Number</dt>
                <dd><?php echo htmlspecialchars($user['phone_number']); ?></dd>

                <dt>Role</dt>
                <dd><?php echo htmlspecialchars($user['role']); ?></dd>
            </dl>

            <h2>Description</h2>
            <div class="description-box">
                <p>
                    <?php
                    echo !empty($user['description']) ? nl2br(htmlspecialchars($user['description'])) : '<em>No description provided.</em>';
                    ?>
                </p>
            </div>
        </div>
    </main>
</body>

</html>