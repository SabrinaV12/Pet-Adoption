<header class="header">
        <nav class="nav">
            <a href="home.php"><img src="assets/logo.png" alt="Home" width="122" height="50" /></a>

             <section class="main-buttons">
                <a href="petSearch.php">Adopt</a>
                <a href="careGuide.php">Care Guide</a>
                <a href="contact.php">Contact</a>
              </section>

              <section class="information-buttons">
            <a href="notification.php"><img src="assets/notifications.png" alt="Notifications" /></a>
            <div class="auth-links">
                 <a href="login.php">Login</a> | <a href="register.php">Register</a>
            </div>
              </section>

        </nav>
        <section class="mini-navigation">
            <a href="home.php">Home > </a>
            <a href="petSearch.php"> Adopt </a>

            <form class="search-bar" action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search..." />
                <button type="submit">
                    <img src="assets/search-glass.png" alt="Search" />
                </button>
            </form>
        </section>
</header>