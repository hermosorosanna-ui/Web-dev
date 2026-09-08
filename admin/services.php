<?php
require 'auth_check.php';
require '../database/config.php';
$pdo = getConnection();

$stmt = $pdo->query("SELECT id,name,description,duration_minutes,is_active FROM services ORDER BY id ASC");
$services = $stmt->fetchAll();
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Services | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css"><link rel="stylesheet" href="assets/admin.css"></head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
<div class="admin-page-heading"><div>
<div class="admin-kicker">ATELIER OFFERINGS</div>
<h1>MANAGE <span>SERVICES</span></h1>
<p>Activate or deactivate services offered for appointment booking.</p>
</div></div>
<?php if ($message): ?><div class="admin-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<section class="service-admin-grid">
<?php foreach ($services as $service): ?>
<article class="service-admin-card">
<div class="service-admin-top"><h2><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></h2>
<span class="status-badge <?= $service['is_active']?'status-confirmed':'status-cancelled' ?>"><?= $service['is_active']?'Active':'Inactive' ?></span></div>
<p><?= htmlspecialchars($service['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
<div class="service-duration"><?= (int)$service['duration_minutes'] ?> minutes</div>
<form method="POST" action="service_action.php">
<input type="hidden" name="service_id" value="<?= (int)$service['id'] ?>">
<input type="hidden" name="action" value="<?= $service['is_active']?'deactivate':'activate' ?>">
<button class="admin-outline-button" type="submit"><?= $service['is_active']?'DEACTIVATE':'ACTIVATE' ?></button>
</form>
</article>
<?php endforeach; ?>
</section>
</main>
</body>
</html>