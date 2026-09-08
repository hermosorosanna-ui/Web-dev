<?php
require 'auth_check.php';
require '../database/config.php';
$pdo = getConnection();

$filter = $_GET['status'] ?? 'all';
$allowed = ['all','pending','confirmed','completed','cancelled'];
if (!in_array($filter, $allowed, true)) $filter = 'all';

if ($filter === 'all') {
    $stmt = $pdo->query(
        "SELECT a.id,a.appointment_date,a.appointment_time,a.notes,a.status,
                u.full_name,u.email,u.phone,s.name AS service_name
         FROM appointments a
         INNER JOIN users u ON u.id=a.user_id
         INNER JOIN services s ON s.id=a.service_id
         ORDER BY a.appointment_date DESC,a.appointment_time DESC"
    );
} else {
    $stmt = $pdo->prepare(
        "SELECT a.id,a.appointment_date,a.appointment_time,a.notes,a.status,
                u.full_name,u.email,u.phone,s.name AS service_name
         FROM appointments a
         INNER JOIN users u ON u.id=a.user_id
         INNER JOIN services s ON s.id=a.service_id
         WHERE a.status=:status
         ORDER BY a.appointment_date DESC,a.appointment_time DESC"
    );
    $stmt->execute([':status'=>$filter]);
}
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointments | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css"><link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
<div class="admin-page-heading"><div>
<div class="admin-kicker">CLIENT SCHEDULE</div>
<h1>MANAGE <span>APPOINTMENTS</span></h1>
<p>Review requests and update appointment status.</p>
</div></div>

<div class="filter-row">
<?php foreach ($allowed as $option): ?>
<a class="<?= $filter===$option?'active':'' ?>" href="appointments.php?status=<?= urlencode($option) ?>"><?= strtoupper($option) ?></a>
<?php endforeach; ?>
</div>

<section class="admin-panel">
<?php if (!$appointments): ?><p class="empty-state">No appointments found.</p>
<?php else: ?>
<div class="admin-table-wrap">
<table class="admin-table appointments-table">
<thead><tr><th>CLIENT</th><th>SERVICE</th><th>DATE / TIME</th><th>NOTES</th><th>STATUS</th><th>ACTION</th></tr></thead>
<tbody>
<?php foreach ($appointments as $row): ?>
<tr>
<td><strong><?= htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8') ?></strong><br><small><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></small><?php if ($row['phone']): ?><br><small><?= htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?></td>
<td><?= htmlspecialchars($row['service_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['appointment_date'], ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars(date('g:i A', strtotime($row['appointment_time'])), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= $row['notes'] ? nl2br(htmlspecialchars($row['notes'], ENT_QUOTES, 'UTF-8')) : '—' ?></td>
<td><span class="status-badge status-<?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?>"><?= ucfirst(htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8')) ?></span></td>
<td><form method="POST" action="appointment_action.php" class="status-form">
<input type="hidden" name="appointment_id" value="<?= (int)$row['id'] ?>">
<select name="status"><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select>
<button type="submit">UPDATE</button>
</form></td>
</tr>
<?php endforeach; ?>
</tbody></table>
</div>
<?php endif; ?>
</section>
</main>
</body>
</html>
