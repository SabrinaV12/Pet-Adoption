<?php
session_start();
require_once 'database/db.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $breed = $_POST['breed'];
    $age = (int)$_POST['age'];
    $color = $_POST['color'];
    $weight = (float)$_POST['weight'];
    $height = (float)$_POST['height'];
    $animal_type = $_POST['animal_type'];
    $size = $_POST['size'];
    $description = $_POST['description'];
    $restrictions = $_POST['restrictions'];
    $recommended = $_POST['recommended'];

    $vaccinated = isset($_POST['vaccinated']);
    $house_trained = isset($_POST['house_trained']);
    $neutered = isset($_POST['neutered']);
    $microchipped = isset($_POST['microchipped']);
    $good_with_children = isset($_POST['good_with_children']);
    $shots_up_to_date = isset($_POST['shots_up_to_date']);

    $feed_dates = $_POST['feed_date'];
    $food_types = $_POST['food_type'];

    $vaccine_ages = $_POST['age_in_weeks'];
    $vaccine_names = $_POST['vaccine_name'];

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO pet_requests (
        name, gender, breed, age, color, weight, height, animal_type, size, user_id, description,
        restrictions, recommended, vaccinated, house_trained, neutered, microchipped,
        good_with_children, shots_up_to_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssisdsssisssiiiiii",
        $name, $gender, $breed, $age, $color, $weight, $height, $animal_type, $size, $user_id,
        $description, $restrictions, $recommended, $vaccinated, $house_trained,
        $neutered, $microchipped, $good_with_children, $shots_up_to_date
    );

    if ($stmt->execute()) {
        $request_id = $conn->insert_id;

        $fc_stmt = $conn->prepare("INSERT INTO feeding_calendar_requests (request_id, feed_date, food_type) VALUES (?, ?, ?)");
        foreach ($feed_dates as $index => $date) {
            $type = $food_types[$index];
            $fc_stmt->bind_param("iss", $request_id, $date, $type);
            $fc_stmt->execute();
        }

        $vac_stmt = $conn->prepare("INSERT INTO vaccinations_requests (request_id, age_in_weeks, vaccine_name) VALUES (?, ?, ?)");
        foreach ($vaccine_ages as $index => $age_week) {
            $name = $vaccine_names[$index];
            $vac_stmt->bind_param("iis", $request_id, $age_week, $name);
            $vac_stmt->execute();
        }

$adminQuery = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$admin = $adminQuery->fetch_assoc();

$adminQuery = $conn->query("SELECT id FROM users WHERE role = 'admin'");
$link = "admin/animal_pages_scripts/admin_pet_requests.php?id=$request_id";
$message = "A fost trimisă o nouă cerere de adopție.";

while ($admin = $adminQuery->fetch_assoc()) {
    $adminId = $admin['id'];
    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
    $notifStmt->bind_param("iss", $adminId, $message, $link);
    $notifStmt->execute();
}


}

header('Location: confirmation.php?from=request');
exit;
        exit;
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Pet for Adoption</title>
    <link rel="stylesheet" href="design/petRequest.css">
</head>
<body>
    <h1>Submit a Pet for Adoption</h1>
    <form action="" method="POST">
        <label>Name: <input type="text" name="name" required></label>
        <label>Gender:
            <select name="gender">
                <option>Male</option>
                <option>Female</option>
            </select>
        </label>
        <label>Breed: <input type="text" name="breed"></label>
        <label>Age: <input type="number" name="age" required></label>
        <label>Color: <input type="text" name="color"></label>
        <label>Weight (kg): <input type="number" step="0.01" name="weight"></label>
        <label>Height (cm): <input type="number" step="0.01" name="height"></label>
        <label>Animal Type:
            <select name="animal_type">
                <option>Dog</option>
                <option>Cat</option>
                <option>Capybara</option>
            </select>
        </label>
        <label>Size:
            <select name="size">
                <option>Small</option>
                <option>Medium</option>
                <option>Large</option>
            </select>
        </label>

        <fieldset>
            <legend>Checks</legend>
            <label><input type="checkbox" name="vaccinated"> Vaccinated</label>
            <label><input type="checkbox" name="house_trained"> House Trained</label>
            <label><input type="checkbox" name="neutered"> Neutered</label>
            <label><input type="checkbox" name="microchipped"> Microchipped</label>
            <label><input type="checkbox" name="good_with_children"> Good with children</label>
            <label><input type="checkbox" name="shots_up_to_date"> Shots up to date</label>
        </fieldset>

        <label>Description: <textarea name="description"></textarea></label>
        <label>Restrictions: <textarea name="restrictions"></textarea></label>
        <label>Recommended: <textarea name="recommended"></textarea></label>

        <h3>Feeding Calendar</h3>
        <div id="feeding-calendar">
            <input type="date" name="feed_date[]" placeholder="Feed Date">
            <input type="text" name="food_type[]" placeholder="Food Type">
        </div>

        <h3>Vaccinations</h3>
        <div id="vaccinations">
            <input type="number" name="age_in_weeks[]" placeholder="Age in weeks">
            <input type="text" name="vaccine_name[]" placeholder="Vaccine Name">
        </div>

        <button type="submit">Submit Request</button>
        
    </form>
</body>
</html>
