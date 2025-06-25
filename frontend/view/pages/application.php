<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
    <link rel="stylesheet" href="/assets/styles/view_application.css">
</head>
<body>
<?php include __DIR__ . '/../components/header.php'; ?>

<h1>Application for <?= htmlspecialchars($application['pet_name']) ?></h1>

<div class="download-buttons">
  <a href="/scripts/download_form.php?id=<?= $application['id'] ?>&format=csv">Download as CSV</a>
  <a href="/scripts/download_form.php?id=<?= $application['id'] ?>&format=json">Download as JSON</a>
</div>

<?php if ($application['status'] === 'pending'): ?>
  <form action="/scripts/decision.php" method="POST">
    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
    <button type="submit" name="decision" value="approve">Approve</button>
    <button type="submit" name="decision" value="deny">Deny</button>
  </form>
<?php else: ?>
  <h3>Final decision: <?= ucfirst($application['status']) ?></h3>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>