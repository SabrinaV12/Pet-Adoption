<?php
session_start();
require_once 'database/check_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="design/petPage.css" />
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<?php include 'components/header.php'; ?>
<link rel="stylesheet" href="design/searchMenu.css">

<div class="container">
  <aside class="sidebar">
  <h3>Filters</h3>
  <form method="GET" action="">
    <fieldset>
      <legend>Choose a pet:</legend>
      <label><input type="checkbox" name="type[]" value="Dog"> Dog</label><br>
      <label><input type="checkbox" name="type[]" value="Cat"> Cat</label><br>
      <label><input type="checkbox" name="type[]" value="Capybara"> Capybara</label><br>
    </fieldset>

    <fieldset>
      <legend>Breed:</legend>
      <select name="breed">
        <option value="">Any</option>
        <option value="Pit Bull">Pit Bull</option>
        <option value="Golden Retriever">Golden Retriever</option>
        <option value="Bull Terrier">Bull Terrier</option>
      </select>
    </fieldset>

    <fieldset>
      <legend>Color:</legend>
      <select name="color">
        <option value="">Any</option>
        <option value="Brown">Brown</option>
        <option value="Black">Black</option>
        <option value="White">White</option>
      </select>
    </fieldset>

    <fieldset>
      <legend>Gender:</legend>
      <label><input type="radio" name="gender" value="Male"> Male</label><br>
      <label><input type="radio" name="gender" value="Female"> Female</label><br>
    </fieldset>

    <fieldset>
      <legend>Size:</legend>
      <label><input type="radio" name="size" value="Small"> Small</label><br>
      <label><input type="radio" name="size" value="Medium"> Medium</label><br>
      <label><input type="radio" name="size" value="Large"> Large</label><br>
    </fieldset>

    <fieldset>
      <legend>Age (max years):</legend>
      <input type="number" name="age" min="0" placeholder="e.g. 5">
    </fieldset>

    <button type="submit">Apply Filter</button>
  </form>
</aside>


  <main class="pet-list">
    <div class="pet-card">
      <img src="assets/Pitter.jpg" alt="Pitter">
      <h4>Pitter</h4>
      <p>Breed: Pit Bull<br>Age: 5 years<br>Size: Large</p>
      <a href="#">More Info</a>
    </div>
    <div class="pet-card">
      <img src="assets/Snoopy.jpg" alt="Snoopy">
      <h4>Snoopy</h4>
      <p>Breed: Cat<br>Age: 4 years<br>Size: Small</p>
      <a href="#">More Info</a>
    </div>
    <div class="pet-card">
      <img src="assets/Balto.jpg" alt="Balto">
      <h4>Balto</h4>
      <p>Breed: Capybara<br>Age: 3 years<br>Size: Medium</p>
      <a href="#">More Info</a>
    </div>
  </main>
</div>

<?php include 'components/footer.php'; ?>
