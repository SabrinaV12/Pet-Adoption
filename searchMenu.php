<?php
session_start();
require_once 'database/check_auth.php';
require_once 'database/db.php';

$conditions = [];
$params = [];
$types = "";

if (!empty($_GET['type'])) {
    $placeholders = implode(',', array_fill(0, count($_GET['type']), '?'));
    $conditions[] = "animal_type IN ($placeholders)";
    $params = array_merge($params, $_GET['type']);
    $types .= str_repeat('s', count($_GET['type']));
}

if (!empty($_GET['breed'])) {
    $conditions[] = "breed = ?";
    $params[] = $_GET['breed'];
    $types .= 's';
}

if (!empty($_GET['color'])) {
    $conditions[] = "color = ?";
    $params[] = $_GET['color'];
    $types .= 's';
}

if (!empty($_GET['gender'])) {
    $conditions[] = "gender = ?";
    $params[] = $_GET['gender'];
    $types .= 's';
}

if (!empty($_GET['size'])) {
    $conditions[] = "size = ?";
    $params[] = $_GET['size'];
    $types .= 's';
}

if (!empty($_GET['age'])) {
    $conditions[] = "age <= ?";
    $params[] = $_GET['age'];
    $types .= 'i';
}

$sql = "SELECT * FROM pets";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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
  <link rel="stylesheet" href="design/searchMenu.css">
</head>

<body>
<?php include 'components/header.php'; ?>

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
    <?php if ($result->num_rows > 0): ?>
      <?php while ($pet = $result->fetch_assoc()): ?>
  <div class="pet-card">
    <img src="<?php echo htmlspecialchars($pet['image_path']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
    <h4><?php echo htmlspecialchars($pet['name']); ?></h4>
    <p>
      Type: <?php echo htmlspecialchars($pet['animal_type']); ?><br>
      Breed: <?php echo htmlspecialchars($pet['breed']); ?><br>
      Age: <?php echo $pet['age']; ?> years<br>
      Size: <?php echo htmlspecialchars($pet['size']); ?>
    </p>
    <a href="petPage.php?id=<?php echo $pet['id']; ?>">More Info</a>
  </div>
<?php endwhile; ?>

    <?php else: ?>
      <p>No pets match your filters.</p>
    <?php endif; ?>
  </main>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
