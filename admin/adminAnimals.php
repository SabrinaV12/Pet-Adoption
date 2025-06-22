<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

$sql = "SELECT id, name, animal_type, breed, gender, adopted FROM pets ORDER BY id DESC";
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
    <title>Manage Animals</title>
    <link rel="stylesheet" href="../design/adminAnimals.css">
</head>

<body>
    <header>
        <h1>Manage Animals</h1>
        <nav>
            <a href="index.php">Go Back to Admin's Main Menu</a>
            <a href="animal_pages_scripts/add_animal.php" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px; text-decoration: none;">+ Add a New Animal</a>
        </nav>
    </header>

    <main>
        <?php if (isset($_GET['status'])): ?>
            <div class="status-message">
                <?php
                if ($_GET['status'] == 'updated') {
                    echo 'Pet details have been updated successfully!';
                } elseif ($_GET['status'] == 'added') {
                    echo 'Pet has been added successfully!';
                } elseif ($_GET['status'] == 'deleted') {
                    echo 'Pet has been deleted successfully!';
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
                    <th>Name</th>
                    <th>Animal Type</th>
                    <th>Breed</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($pet = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $pet['id']; ?></td>
                            <td><?php echo htmlspecialchars($pet['name']); ?></td>
                            <td><?php echo htmlspecialchars($pet['animal_type']); ?></td>
                            <td><?php echo htmlspecialchars($pet['breed']); ?></td>
                            <td><?php echo htmlspecialchars($pet['gender']); ?></td>
                            <td>
                                <?php
                                echo $pet['adopted'] ? '<span style="color: green;">Adopted</span>' : 'Available';
                                ?>
                            </td>
                            <td class="actions">
                                <a href="animal_pages_scripts/details_animal.php?id=<?php echo $pet['id']; ?>" class="view">Details</a>
                                <a href="animal_pages_scripts/edit_animal.php?id=<?php echo $pet['id']; ?>" class="edit">Edit</a>
                                <a href="animal_pages_scripts/delete_animal.php?id=<?php echo $pet['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>

<?php
$conn->close();
?>