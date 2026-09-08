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



    <section class="stat-grid">
        <article class="stat-card"><span>TOTAL APPOINTMENTS</span><strong><?= $stats['total'] ?></strong></article>
        <article class="stat-card"><span>PENDING</span><strong><?= $stats['pending'] ?></strong></article>
        <article class="stat-card"><span>CONFIRMED</span><strong><?= $stats['confirmed'] ?></strong></article>
        <article class="stat-card"><span>CUSTOMERS</span><strong><?= $stats['customers'] ?></strong></article>
    </section>

    <section class="admin-panel">
        <div class="panel-heading">
            <h2>APPOINTMENTS</h2>
            <a href="appointment_create.php">+ ADD APPOINTMENT</a>
        </div>

        <?php if ($message = ($_GET['message'] ?? null)): ?>
            <div class="admin-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!$appointments): ?>
            <p class="empty-state">No appointments found.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table appointments-table">
                    <thead>
                        <tr>
                            <th>CLIENT</th>
                            <th>SERVICE</th>
                            <th>DATE / TIME</th>
                            <th>NOTES</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($appointments as $row): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                <small><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['service_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?= htmlspecialchars($row['appointment_date'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?= htmlspecialchars(date('g:i A', strtotime($row['appointment_time'])), ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= $row['notes']
                                    ? nl2br(htmlspecialchars($row['notes'], ENT_QUOTES, 'UTF-8'))
                                    : '—' ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= ucfirst(htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8')) ?>
                                </span>
                            </td>
                            <td>
                                <div class="appointment-actions">
                                    <a href="appointment_edit.php?id=<?= (int)$row['id'] ?>" class="admin-outline-button">EDIT</a>
                                    <form method="POST" action="appointment_action.php" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        <input type="hidden" name="appointment_id" value="<?= (int)$row['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="admin-delete-button">DELETE</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
