<?php
require_once 'database/db.php';

$conditions = [];
$params = [];
$types = "";
$feedTitle = "Animals up to adoption";

$conditions[] = "adopted = 0";

if (!empty($_GET['type'])) {
    $placeholders = implode(',', array_fill(0, count($_GET['type']), '?'));
    $conditions[] = "animal_type IN ($placeholders)";
    $params = array_merge($params, $_GET['type']);
    $types .= str_repeat('s', count($_GET['type']));
    $feedTitle = htmlspecialchars(implode(', ', $_GET['type'])) . " adoptions";
}

if (!empty($_GET['breed'])) {
    $conditions[] = "breed = ?";
    $params[] = $_GET['breed'];
    $types .= 's';
}

$sql = "SELECT * FROM pets  WHERE " . implode(" AND ", $conditions);

$sql .= " ORDER BY created_at DESC LIMIT 25";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: application/rss+xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
    <channel>
        <title><?php echo $feedTitle; ?></title>
        <link>http://<?php echo $_SERVER['HTTP_HOST']; ?></link>
        <description>The most recent offers based on your filters.</description>

        <?php while ($pet = $result->fetch_assoc()): ?>
            <item>
                <title><?php echo htmlspecialchars($pet['name'] . ' - ' . $pet['breed']); ?></title>
                <?php
                $petUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/petPage.php?id=' . $pet['id'];
                ?>
                <link><?php echo $petUrl; ?></link>
                <guid isPermaLink="true"><?php echo $petUrl; ?></guid>

                <description>
                    <![CDATA[
                <img src="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($pet['image_path']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" width="200"><br>
                <strong>Type:</strong> <?php echo htmlspecialchars($pet['animal_type']); ?><br>
                <strong>Age:</strong> <?php echo $pet['age']; ?> years<br>
                <strong>Gender:</strong> <?php echo htmlspecialchars($pet['gender']); ?>
            ]]>
                </description>

                <pubDate><?php echo date(DATE_RSS, strtotime($pet['created_at'])); ?></pubDate>
            </item>
        <?php endwhile; ?>

    </channel>
</rss>