<?php
session_start();
require_once 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$current_user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

$update_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$update_stmt->bind_param("i", $current_user_id);
$update_stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="design/header.css" />
    <link rel="stylesheet" href="design/footer.css" />
    <style>
        .notifications-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .notification {
            display: block;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
        }

        .notification:hover {
            background-color: #f9f9f9;
        }

        .notification.unread {
            background-color: #e8f0fe;
            border-left: 5px solid #1a73e8;
            font-weight: bold;
        }

        .notification.read {
            background-color: #f1f1f1;
            color: #666;
        }

        .notification-time {
            display: block;
            font-size: 0.8em;
            color: #888;
            margin-top: 5px;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <?php include 'components/header.php'; ?>

    <div class="notifications-container">
        <h1>My Notifications</h1>

        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <?php
                $status_class = ($notification['is_read'] == 0) ? 'unread' : 'read';
                ?>

                <a href="<?php echo htmlspecialchars($notification['link']); ?>" class="notification <?php echo $status_class; ?>">
                    <?php echo htmlspecialchars($notification['message']); ?>
                    <span class="notification-time">
                        <?php echo date('d M Y, H:i', strtotime($notification['created_at'])); ?>
                    </span>
                </a>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>

    </div>

    <?php include 'components/footer.php'; ?>
</body>

</html>