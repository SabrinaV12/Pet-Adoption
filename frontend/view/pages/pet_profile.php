<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pet['name']) ?>'s Profile</title>
  <link rel="stylesheet" href="/assets/styles/petPage.css">
  <link rel="stylesheet" href="/assets/styles/header.css">
  <link rel="stylesheet" href="/assets/styles/footer.css">
  <link rel="stylesheet" href="/assets/styles/searchMenu.css">
  <style>
    .checks-description-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      margin-top: 30px;
    }

    .description-box {
      flex: 2;
      background: #f7f7f7;
      padding: 20px;
      border-radius: 10px;
    }

    .checks-box {
      flex: 1;
      background: #ffffff;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
    }

    .checks-box div {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div id="header-placeholder"></div>

  <div class="banner" style="background-image: url('<?= $pet['image_path'] ?>');"></div>
  <div class="profile-container">
    <div class="profile-pic">
      <img src="<?= $pet['image_path'] ?>" alt="<?= htmlspecialchars($pet['name']) ?>">
    </div>
    <h2><?= htmlspecialchars($pet['name']) ?></h2>
    <p class="location"><?= htmlspecialchars($country) ?> · <?= htmlspecialchars($county) ?></p>

    <?php if ($pet['adopted']): ?>
      <div class="adopted">Adopted on <?= htmlspecialchars($pet['adoption_date']) ?></div>
    <?php else: ?>
      <div class="adopted">
        <a href="/adoptionStart.php?pet_id=<?= $pet['id']; ?>" class="adopt-button">Adopt</a>
      </div>
    <?php endif; ?>

    <div class="info-cards">
      <div>Gender: <?= $pet['gender'] ?></div>
      <div>Breed: <?= $pet['breed'] ?></div>
      <div>Age: <?= $pet['age'] ?> years</div>
      <div>Color: <?= $pet['color'] ?></div>
      <div>Weight: <?= $pet['weight'] ?> kg</div>
      <div>Animal: <?= $pet['animal_type'] ?></div>
    </div>

    <div class="checks-description-wrapper">
      <div class="description-box">
        <h3><?= $pet['name'] ?>'s Story</h3>
        <p><?= $pet['description'] ?: 'No description provided.' ?></p>
      </div>

      <div class="checks-box">
        <div>Can live with children: <?= formatBoolean($pet['good_with_children']) ?></div>
        <div>Vaccinated: <?= formatBoolean($pet['vaccinated']) ?></div>
        <div>House-Trained: <?= formatBoolean($pet['house_trained']) ?></div>
        <div>Neutered: <?= formatBoolean($pet['neutered']) ?></div>
        <div>Shots up to date: <?= formatBoolean($pet['shots_up_to_date']) ?></div>
        <div>Microchipped: <?= formatBoolean($pet['microchipped']) ?></div>
      </div>
    </div>

    <div class="section">
      <h3>Restrictions</h3>
      <p><?= $pet['restrictions'] ?: 'None' ?></p>
    </div>

    <div class="section">
      <h3>Recommended</h3>
      <p><?= $pet['recommended'] ?: 'None' ?></p>
    </div>

    <div class="section">
      <h3>Vaccination History</h3>
      <table>
        <tr>
          <th>Age (weeks)</th>
          <th>Vaccine</th>
        </tr>
        <?php foreach ($vaccines as $vac): ?>
          <tr>
            <td><?= $vac['age_in_weeks'] ?></td>
            <td><?= htmlspecialchars($vac['vaccine_name']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>

    <div class="section">
      <h3>Feeding Calendar</h3>
      <?php
      $year = date('Y');
      $month = date('m');
      $monthFeedings = array_filter($feedings, fn($f) => date('Y-m', strtotime($f['feed_date'])) === "$year-$month");
      $feedingMap = [];
      foreach ($monthFeedings as $f) {
        $day = date('j', strtotime($f['feed_date']));
        $feedingMap[$day] = $f['food_type'];
      }
      $firstDay = date('N', strtotime("$year-$month-01"));
      $daysInMonth = date('t');
      ?>
      <div class="calendar">
        <div class="calendar-header"><?= date('F Y') ?></div>
        <div class="calendar-grid">
          <?php foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $dow): ?>
            <div class="day-name"><?= $dow ?></div>
          <?php endforeach; ?>
          <?php for ($i = 1; $i < $firstDay; $i++): ?>
            <div class="day empty"></div>
          <?php endfor; ?>
          <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php if (isset($feedingMap[$day])): ?>
              <div class="day feeding">
                <?= $day ?>
                <div class="tooltip"><?= htmlspecialchars($feedingMap[$day]) ?></div>
              </div>
            <?php else: ?>
              <div class="day"><?= $day ?></div>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      </div>
    </div>

    <div class="section">
      <h3>Basic First Aid Tips for <?= htmlspecialchars($pet['animal_type']) ?>s</h3>
      <p>
        <?php
        switch (strtolower($pet['animal_type'])) {
          case 'dog':
            echo "If your dog is injured...";
            break;
          case 'cat':
            echo "For minor cuts or wounds...";
            break;
          case 'capybara':
            echo "Capybaras are prone to wounds...";
            break;
          default:
            echo "No specific first aid information available.";
        }
        ?>
      </p>
    </div>

    <div class="section">
      <h3>Media Gallery</h3>
      <?php if (!empty($mediaFiles)): ?>
        <div class="media-gallery">
          <?php foreach ($mediaFiles as $media): ?>
            <div class="media-item">
              <?php if ($media['file_type'] === 'image'): ?>
                <img src="<?= htmlspecialchars($media['file_path']) ?>" alt="Pet Photo" style="max-width: 100%; border-radius: 10px;" />
              <?php elseif ($media['file_type'] === 'video'): ?>
                <video controls style="max-width: 100%; border-radius: 10px;">
                  <source src="<?= htmlspecialchars($media['file_path']) ?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              <?php elseif ($media['file_type'] === 'audio'): ?>
                <audio controls style="width: 100%;">
                  <source src="<?= htmlspecialchars($media['file_path']) ?>" type="audio/mpeg">
                  Your browser does not support the audio tag.
                </audio>
              <?php endif; ?>
              <?php if ($isOwner): ?>
                <form action="/delete_pet_media.php" method="POST" onsubmit="return confirm('Are you sure?');" style="margin-top: 10px;">
                  <input type="hidden" name="file_path" value="<?= htmlspecialchars($media['file_path']) ?>">
                  <input type="hidden" name="pet_id" value="<?= $petId ?>">
                  <button type="submit" style="background-color: #cc0000; color: white; border: none; padding: 6px 12px; border-radius: 6px;">Delete</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No media files available for this pet.</p>
      <?php endif; ?>
    </div>

    <?php if ($isOwner): ?>
      <div class="section">
        <h3>Upload Media</h3>
        <form action="/upload_pet_media.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="pet_id" value="<?= $petId ?>">
          <div class="form-group">
            <label for="media">Choose file (image, video, or audio):</label>
            <input type="file" name="media" accept="image/*,video/*,audio/*" required>
          </div>
          <button type="submit">Upload</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <div id="footer-placeholder"></div>

  <script src="../../controller/footerController.js"></script>
  <script src="../../controller/headerController.js"></script>
</body>

</html>