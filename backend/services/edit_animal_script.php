<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Access Denied.");
}

$id = $_POST['id'];
$name = $_POST['name'];
$animal_type = $_POST['animal_type'];
$breed = $_POST['breed'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$description = $_POST['description'];
$color = $_POST['color'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$size = $_POST['size'];
$restrictions = $_POST['restrictions'];
$recommended = $_POST['recommended'];
$adoption_date = !empty($_POST['adoption_date']) ? $_POST['adoption_date'] : NULL;

$vaccinated = isset($_POST['vaccinated']) ? 1 : 0;
$neutered = isset($_POST['neutered']) ? 1 : 0;
$house_trained = isset($_POST['house_trained']) ? 1 : 0;
$microchipped = isset($_POST['microchipped']) ? 1 : 0;
$good_with_children = isset($_POST['good_with_children']) ? 1 : 0;
$shots_up_to_date = isset($_POST['shots_up_to_date']) ? 1 : 0;
$adopted = isset($_POST['adopted']) ? 1 : 0;

$new_image_path = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_ext) && $_FILES['image']['size'] < 5 * 1024 * 1024) {
        $unique_file_name = uniqid('', true) . '.' . $file_ext;
        $upload_dir = '../../assets/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $image_path_server = $upload_dir . $unique_file_name;

        $sql_get_old_image = "SELECT image_path FROM pets WHERE id = ?";
        $stmt_get_old = $conn->prepare($sql_get_old_image);
        $stmt_get_old->bind_param('i', $id);
        $stmt_get_old->execute();
        $result_old = $stmt_get_old->get_result();
        if ($old_pet = $result_old->fetch_assoc()) {
            if (!empty($old_pet['image_path']) && file_exists('../../' . $old_pet['image_path'])) {
                unlink('../../' . $old_pet['image_path']);
            }
        }
        $stmt_get_old->close();

        if (move_uploaded_file($file_tmp, $image_path_server)) {
            $new_image_path = 'assets/' . $unique_file_name;
        }
    }
}

$sql_parts = [];
$params = [];
$types = '';

$sql_parts = [
    "name = ?",
    "gender = ?",
    "breed = ?",
    "age = ?",
    "color = ?",
    "weight = ?",
    "height = ?",
    "animal_type = ?",
    "size = ?",
    "vaccinated = ?",
    "house_trained = ?",
    "neutered = ?",
    "microchipped = ?",
    "good_with_children = ?",
    "shots_up_to_date = ?",
    "restrictions = ?",
    "recommended = ?",
    "description = ?",
    "adopted = ?",
    "adoption_date = ?"
];

$types = 'sssisddssiiiiissssss';
$params = [
    $name,
    $gender,
    $breed,
    $age,
    $color,
    $weight,
    $height,
    $animal_type,
    $size,
    $vaccinated,
    $house_trained,
    $neutered,
    $microchipped,
    $good_with_children,
    $shots_up_to_date,
    $restrictions,
    $recommended,
    $description,
    $adopted,
    $adoption_date
];

if ($new_image_path !== null) {
    $sql_parts[] = "image_path = ?";
    $params[] = $new_image_path;
    $types .= 's';
}

$sql = "UPDATE pets SET " . implode(', ', $sql_parts) . " WHERE id = ?";
$params[] = $id;
$types .= 'i';

$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: ../adminAnimals.php?status=updated");
    exit;
} else {
    header("Location: ../adminAnimals.php?status=error&msg=" . urlencode($stmt->error));
    exit;
}

$stmt->close();
$conn->close();
