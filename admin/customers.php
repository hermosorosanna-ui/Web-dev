<?php
require 'auth_check.php';
require '../database/config.php';
$pdo = getConnection();

$stmt = $pdo->query(
    "SELECT u.full_name,u.email,u.phone,u.created_at,COUNT(a.id) AS appointment_count
     FROM users u
     LEFT JOIN appointments a ON a.user_id=u.id
     WHERE u.role='customer'
     GROUP BY u.id,u.full_name,u.email,u.phone,u.created_at
     ORDER BY u.created_at DESC"
);
$customers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customers | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css"><link rel="stylesheet" href="assets/admin.css"></head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
<div class="admin-page-heading"><div>
<div class="admin-kicker">CLIENT DIRECTORY</div>
<h1>OUR <span>CUSTOMERS</span></h1>
<p>View registered clients and appointment counts.</p>
</div></div>
<section class="admin-panel">
<div class="admin-table-wrap">
<?php if (!$customers): ?><p class="empty-state">No customer accounts yet.</p>
<?php else: ?>
<table class="admin-table">
<thead><tr><th>NAME</th><th>EMAIL</th><th>PHONE</th><th>APPOINTMENTS</th><th>REGISTERED</th></tr></thead>
<tbody>
<?php foreach ($customers as $customer): ?>
<tr>
<td><?= htmlspecialchars($customer['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= $customer['phone'] ? htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
<td><?= (int)$customer['appointment_count'] ?></td>
<td><?= htmlspecialchars(date('M j, Y', strtotime($customer['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
</section>
</main>
</body>
</html>