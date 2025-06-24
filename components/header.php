<header class="header">
    <nav class="nav">
        <a href="home.php"><img src="assets/logo.png" alt="Home" width="122" height="50" /></a>

        <section class="main-buttons">
            <a href="searchMenu.php">Adopt</a>
            <a href="petRequest.php">Rehome a Pet</a>
            <a href="documentatie/documentatie.html">About</a>
        </section>

        <section class="information-buttons">
            <a href="notification.php"><img src="assets/notifications.png" alt="Notifications" /></a>

            <div class="auth-links">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <a href="userProfile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        | <a href="admin/index.php" style="color: #9990DA; font-weight: bold;">Admin Panel</a>
                    <?php endif; ?>

                    | <a href="database/logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a> | <a href="register.php">Register</a>
                <?php endif; ?>
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
