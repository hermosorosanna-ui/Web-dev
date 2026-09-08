<?php
require 'auth_check.php';
require '../database/config.php';

$pdo = getConnection();

$stats = [
    'total' => (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
    'pending' => (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='pending'")->fetchColumn(),
    'confirmed' => (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='confirmed'")->fetchColumn(),
    'customers' => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn()
];

$stmt = $pdo->query(
    "SELECT
        a.id,
        a.appointment_date,
        a.appointment_time,
        a.notes,
        a.status,
        u.full_name,
        u.email,
        s.name AS service_name
     FROM appointments a
     INNER JOIN users u ON u.id = a.user_id
     INNER JOIN services s ON s.id = a.service_id
     ORDER BY a.appointment_date DESC,
              a.appointment_time DESC"
);

$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Hermoso Atelier</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">ATELIER MANAGEMENT</div>
            <h1>ADMIN <span>DASHBOARD</span></h1>
            <p>Welcome back, <?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8') ?>.</p>
        </div>
    </div>

    <section class="stat-grid">
        <article class="stat-card"><span>TOTAL APPOINTMENTS</span><strong><?= $stats['total'] ?></strong></article>
        <article class="stat-card"><span>PENDING</span><strong><?= $stats['pending'] ?></strong></article>
        <article class="stat-card"><span>CONFIRMED</span><strong><?= $stats['confirmed'] ?></strong></article>
        <article class="stat-card"><span>CUSTOMERS</span><strong><?= $stats['customers'] ?></strong></article>
    </section>

 
</main>
</body>
</html>
