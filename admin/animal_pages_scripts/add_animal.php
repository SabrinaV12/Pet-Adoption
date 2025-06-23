<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('location: ../../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Pet</title>
    <link rel="stylesheet" href="../../design/add_animal.css">
</head>

<body>
    <header>
        <h1>Add a New Pet</h1>
        <a href="../adminAnimals.php">Back to Animals List</a>
    </header>

    <main>
        <form action="add_animal_script.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <fieldset>
                <legend>Core Details</legend>
                <div class="form-group">
                    <label for="animal_type">Animal Type:</label>
                    <select id="animal_type" name="animal_type" required>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Capybara">Capybara</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="breed">Breed:</label>
                    <input type="text" id="breed" name="breed">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required>
                </div>
            </fieldset>

            <fieldset>
                <legend>Physical Details</legend>
                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" id="color" name="color">
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" step="0.01" id="weight" name="weight">
                </div>
                <div class="form-group">
                    <label for="height">Height (cm):</label>
                    <input type="number" step="0.01" id="height" name="height">
                </div>
                <div class="form-group">
                    <label for="size">Size:</label>
                    <select id="size" name="size" required>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend>Descriptions</legend>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5"></textarea>
                </div>
                <div class="form-group"><label for="restrictions">Restrictions:</label><textarea name="restrictions" rows="3"></textarea></div>
                <div class="form-group"><label for="recommended">Recommendations:</label><textarea name="recommended" rows="3"></textarea></div>
            </fieldset>

            <fieldset>
                <legend>Health & Behavior</legend>
                <div class="checkbox-group"><input type="checkbox" id="vaccinated" name="vaccinated" value="1"><label for="vaccinated">Vaccinated</label></div>
                <div class="checkbox-group"><input type="checkbox" id="neutered" name="neutered" value="1"><label for="neutered">Neutered</label></div>
                <div class="checkbox-group"><input type="checkbox" id="microchipped" name="microchipped" value="1"><label for="microchipped">Microchipped</label></div>
                <div class="checkbox-group"><input type="checkbox" id="good_with_children" name="good_with_children" value="1"><label for="good_with_children">Good with Children</label></div>
                <div class="checkbox-group"><input type="checkbox" id="shots_up_to_date" name="shots_up_to_date" value="1"><label for="shots_up_to_date">Shots Up-to-Date</label></div>
                <div class="checkbox-group"><input type="checkbox" id="house_trained" name="house_trained" value="1"><label for="house_trained">House Trained</label></div>
            </fieldset>

            <fieldset>
                <legend>Adoption Details</legend>
                <div class="checkbox-group"><input type="checkbox" id="adopted" name="adopted" value="1"><label for="adopted">Adopted</label></div>
                <div class="form-group">
                    <label for="adoption_date">Adoption Date:</label>
                    <input type="date" id="adoption_date" name="adoption_date">
                </div>
            </fieldset>

            <fieldset>
                <legend>Pet Image</legend>
                <div class="form-group">
                    <label for="image">Upload Image:</label>
                    <input type="file" id="image" name="image">
                </div>
            </fieldset>

            <fieldset>
  <legend>Feeding Calendar</legend>

  <div class="form-group">
    <label>Feed Date 1: <input type="date" name="feed_date[]"></label>
    <label>Food Type 1: <input type="text" name="food_type[]"></label>
  </div>

  <div class="form-group">
    <label>Feed Date 2: <input type="date" name="feed_date[]"></label>
    <label>Food Type 2: <input type="text" name="food_type[]"></label>
  </div>

  <div class="form-group">
    <label>Feed Date 3: <input type="date" name="feed_date[]"></label>
    <label>Food Type 3: <input type="text" name="food_type[]"></label>
  </div>
</fieldset>

<fieldset>
  <legend>Vaccinations</legend>

  <div class="form-group">
    <label>Age in weeks 1: <input type="number" name="age_in_weeks[]"></label>
    <label>Vaccine Name 1: <input type="text" name="vaccine_name[]"></label>
  </div>

  <div class="form-group">
    <label>Age in weeks 2: <input type="number" name="age_in_weeks[]"></label>
    <label>Vaccine Name 2: <input type="text" name="vaccine_name[]"></label>
  </div>

  <div class="form-group">
    <label>Age in weeks 3: <input type="number" name="age_in_weeks[]"></label>
    <label>Vaccine Name 3: <input type="text" name="vaccine_name[]"></label>
  </div>
</fieldset>


            <button type="submit">Add Pet to Database</button>
        </form>
    </main>
</body>

</html>