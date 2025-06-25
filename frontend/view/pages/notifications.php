<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="/assets/styles/header.css" />
    <link rel="stylesheet" href="/assets/styles/footer.css" />
    <link rel="stylesheet" href="/assets/styles/notifications.css" />
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <div class="notifications-container">
        <h1>My Notifications</h1>

        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <?php
                    $status_class = ($notification['is_read'] == 0) ? 'unread' : 'read';
                ?>
                <a href="<?= htmlspecialchars($notification['link']) ?>" class="notification <?= $status_class ?>">
                    <?= htmlspecialchars($notification['message']) ?>
                    <span class="notification-time">
                        <?= date('d M Y, H:i', strtotime($notification['created_at'])) ?>
                    </span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
