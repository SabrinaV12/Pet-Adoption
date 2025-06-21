<?php
session_start();
require_once 'database/check_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="design/petPage.css" />
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
</head>

<?php include 'components/header.php'; ?>
<link rel="stylesheet" href="design/userProfile.css">

<header class="profile-header">
    <img class="banner" src="assets/cherry.jpg" alt="Banner Image" />
    <div class="profile-image-container">
      <img class="profile-image" src="assets/woman.jpg" alt="Profile" />
    </div>
    <h1 class="username">Lilly Smith</h1>
    <p class="location">
      <img src="assets/USA_flag.png" alt="Flag" class="flag" />
      United States Of America · California
    </p>
  </header>

  <main class="profile-main">
    <section class="bio-contact">
      <div class="bio">
        <p>Hello! I’m Lilly and I’m 27 years old. I live with my husband and Papi (my dog). Papi has been living with us for 5 years and needs a friend and playmate. That’s why now that we have a yard, we thought of adopting a dog.</p>
      </div>
      <div class="contact">
        <h3>Contact me!</h3>
        <ul>
          <li>Email: <a href="mailto:SamantaSmith@gmail.com">SamantaSmith@gmail.com</a></li>
          <li>Phone: 702-684-2621</li>
          <li>Address: Las Vegas 1028 Hall Street</li>
          <li>Telegram: @Samanta.S</li>
        </ul>
      </div>
    </section>

    <section class="pet-listings">
      <h2>Pets that I have up for adoption:</h2>
      <div class="pets-grid">
        <div class="pet-card">
          <img src="assets/pitter.jpg" alt="Pitter" />
          <p><strong>Pitter</strong></p>
          <p>California, USA<br>Male · Pit Bull<br>5 years · Large</p>
        </div>
        <div class="pet-card">
          <img src="assets/snoopy.jpg" alt="Snoopy" />
          <p><strong>Snoopy</strong></p>
          <p>Ohio, USA<br>Male · Bull Terrier<br>4 years · Big</p>
        </div>
        <div class="pet-card">
          <img src="assets/balto.jpg" alt="Balto" />
          <p><strong>Balto</strong></p>
          <p>Michigan, USA<br>Male · Golden Retriever<br>3 years · Large</p>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2025 FurryFriends.com</p>
  </footer>

</body>
</html>

<?php include 'components/footer.php'; ?>
