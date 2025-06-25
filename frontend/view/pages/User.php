<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>'s Profile</title>
  <link rel="stylesheet" href="/assets/styles/petPage.css" />
  <link rel="stylesheet" href="/assets/styles/header.css" />
  <link rel="stylesheet" href="/assets/styles/footer.css" />
  <link rel="stylesheet" href="/assets/styles/userProfile.css">
</head>

<body>
<?php include __DIR__ . '/../components/header.php'; ?>

<header class="profile-header">
  <img class="banner" src="/assets/<?= htmlspecialchars($user['banner_picture']) ?>" alt="Banner Image" />
  <div class="profile-image-container">
    <img class="profile-image" src="/assets/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" />
  </div>
  <h1 class="username"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
  <p class="location">
    <?= htmlspecialchars($user['country']) ?> · <?= htmlspecialchars($user['county']) ?>
  </p>
</header>

<main class="profile-main">
  <section class="bio-contact">
    <div class="bio">
      <p><?= nl2br(htmlspecialchars($user['description'] ?: 'No bio added yet.')) ?></p>
    </div>
    <div class="contact">
      <h3>Contact me!</h3>
      <ul>
        <li>Email: <a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></li>
        <li>Phone: <?= htmlspecialchars($user['phone_number']) ?></li>
        <li>Telegram: @<?= htmlspecialchars($user['telegram_handle']) ?></li>
      </ul>
    </div>
  </section>

  <section class="pet-listings">
    <h2>Pets that I have up for adoption:</h2>
    <div class="pets-grid">
    <?php if (count($pets) > 0): ?>
      <?php foreach ($pets as $pet): ?>
        <div class="pet-card">
          <img src="<?= htmlspecialchars($pet['image_path']) ?>" alt="<?= htmlspecialchars($pet['name']) ?>" />
          <p><strong><?= htmlspecialchars($pet['name']) ?></strong></p>
          <p>
            <?= htmlspecialchars($pet['animal_type']) ?> · <?= htmlspecialchars($pet['gender']) ?> · <?= htmlspecialchars($pet['breed']) ?><br>
            <?= htmlspecialchars($pet['age']) ?> years · <?= htmlspecialchars($pet['size']) ?>
          </p>
          <a href="/petPage.php?id=<?= $pet['id'] ?>" class="more-info-button">More Info</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No pets listed yet.</p>
    <?php endif; ?>
    </div>
  </section>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
