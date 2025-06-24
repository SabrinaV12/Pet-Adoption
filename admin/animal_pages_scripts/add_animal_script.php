<?php
session_start();
require_once '../../database/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Access Denied.");
}

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

$image_path_to_db = null;

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
        $image_path_on_server = $upload_dir . $unique_file_name;

        if (move_uploaded_file($file_tmp, $image_path_on_server)) {
            $image_path_to_db = 'assets/' . $unique_file_name;
        }
    }
}

$user_id = $_SESSION['user_id'];


$sql = "INSERT INTO pets (
            user_id,name, gender, breed, age, color, weight, height, animal_type, size, 
            vaccinated, house_trained, neutered, microchipped, good_with_children, 
            shots_up_to_date, restrictions, recommended, description, adopted, adoption_date, image_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$types = 'isssisddssiiiiiisssiss';


$stmt->bind_param(
    $types,
    $user_id,
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
    $adoption_date,
    $image_path_to_db
);

if ($stmt->execute()) {
    $pet_id = $conn->insert_id;

    $feed_dates = $_POST['feed_date'];
    $food_types = $_POST['food_type'];

    $fc_stmt = $conn->prepare("INSERT INTO feeding_calendar (pet_id, feed_date, food_type) VALUES (?, ?, ?)");
    foreach ($feed_dates as $index => $date) {
        if (!empty($date) && !empty($food_types[$index])) {
            $fc_stmt->bind_param("iss", $pet_id, $date, $food_types[$index]);
            $fc_stmt->execute();
        }
    }

    $vaccine_ages = $_POST['age_in_weeks'];
    $vaccine_names = $_POST['vaccine_name'];

    $vac_stmt = $conn->prepare("INSERT INTO vaccinations (pet_id, age_in_weeks, vaccine_name) VALUES (?, ?, ?)");
    foreach ($vaccine_ages as $index => $age_week) {
        if (!empty($age_week) && !empty($vaccine_names[$index])) {
            $vac_stmt->bind_param("iis", $pet_id, $age_week, $vaccine_names[$index]);
            $vac_stmt->execute();
        }
    }

    header("Location: ../adminAnimals.php?status=added");
    exit;
} else {
    header("Location: ../adminAnimals.php?status=error&msg=" . urlencode($stmt->error));
    exit;
}

$stmt->close();
$conn->close();
