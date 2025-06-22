<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

$sql = "SELECT id, username, email, role FROM users ORDER BY id ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../design/adminUsers.css">
</head>

<body>
    <header>
        <h1>Manage Users</h1>
        <nav>
            <a href="index.php">Back to Admin's Main Menu</a>
            <a href="user_pages_scripts/add_user.php" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px; text-decoration: none;">+ Add a New User</a>
        </nav>
    </header>

    <main>
        <?php if (isset($_GET['status'])): ?>
            <div class="status-message">
                <?php
                if ($_GET['status'] == 'updated') {
                    echo 'User details have been updated successfully!';
                } elseif ($_GET['status'] == 'added') {
                    echo 'User has been added successfully!';
                } elseif ($_GET['status'] == 'deleted') {
                    echo 'User has been deleted successfully!';
                } elseif ($_GET['status'] == 'error') {
                    echo 'An error occurred: ' . htmlspecialchars($_GET['msg']);
                }
                ?>
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="actions">
                                <a href="user_pages_scripts/details_user.php?id=<?php echo $user['id']; ?>" class="view">Details</a>
                                <a href="user_pages_scripts/edit_user.php?id=<?php echo $user['id']; ?>" class="edit">Edit</a>
                                <a href="user_pages_scripts/delete_user.php?id=<?php echo $user['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found in the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>