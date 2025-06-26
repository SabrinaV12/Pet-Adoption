<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search</title>
  <link rel="stylesheet" href="/assets/styles/petPage.css" />
  <link rel="stylesheet" href="/assets/styles/header.css" />
  <link rel="stylesheet" href="/assets/styles/footer.css" />
  <link rel="stylesheet" href="/assets/styles/searchMenu.css">
</head>

<body>
  <div id="header-placeholder"></div>

  <div class="container">
    <aside class="sidebar">
      <h3>Filters</h3>
      <form method="GET" action="">
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

        <fieldset>

          <fieldset>
            <legend>Country:</legend>
            <select name="country">
              <option value="">Any</option>
              <?php foreach ($countries as $c): ?>
                <option value="<?= htmlspecialchars($c['country']) ?>" <?= (isset($_GET['country']) && $_GET['country'] === $c['country']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['country']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </fieldset>

          <fieldset>
            <legend>County:</legend>
            <select name="county">
              <option value="">Any</option>
              <?php foreach ($counties as $c): ?>
                <option value="<?= htmlspecialchars($c['county']) ?>" <?= (isset($_GET['county']) && $_GET['county'] === $c['county']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['county']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </fieldset>

          <button type="submit">Apply Filter</button>

          <div class="rss-link-container" style="margin-bottom: 20px; text-align: right;">
            <?php $query_string = http_build_query($_GET); ?>
            <a href="/rss.php?<?php echo $query_string; ?>" target="_blank">
              <img src="https://upload.wikimedia.org/wikipedia/en/4/43/Feed-icon.svg" alt="RSS Feed" style="width: 24px; height: 24px; vertical-align: middle;">
              Subscribe to this search
            </a>
          </div>
      </form>
    </aside>

    <main class="pet-list">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($pet = $result->fetch_assoc()): ?>
          <div class="pet-card">
            <img src="<?= htmlspecialchars($pet['image_path']); ?>" alt="<?= htmlspecialchars($pet['name']); ?>">
            <h4><?= htmlspecialchars($pet['name']); ?></h4>
            <p>
              Type: <?= htmlspecialchars($pet['animal_type']); ?><br>
              Breed: <?= htmlspecialchars($pet['breed']); ?><br>
              Age: <?= $pet['age']; ?> years<br>
              Size: <?= htmlspecialchars($pet['size']); ?>
            </p>
            <a href="/petPage.php?id=<?= $pet['id']; ?>">More Info</a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No pets match your filters.</p>
      <?php endif; ?>
    </main>
  </div>

  <div id="footer-placeholder"></div>

  <script src="../../controller/footerController.js"></script>
  <script src="../../controller/headerController.js"></script>
</body>

</html>