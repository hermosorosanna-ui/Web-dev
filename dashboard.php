<?php

// Customer dashboard must read the same session created by login_function.php.
session_name('HERMOSO_SESSION');
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'customer') {
    header('Location: login.php');
    exit;
}

require 'database/config.php';

$pdo = getConnection();

$sql = "SELECT a.id, a.appointment_date, a.appointment_time, a.status,
               s.name AS service_name
        FROM appointments a
        INNER JOIN services s ON s.id = a.service_id
        WHERE a.user_id = :user_id
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$appointments = $stmt->fetchAll();

$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Hermoso Atelier</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="dashboard-page">

<header class="site-header">
    <a class="brand" href="index.php">
        <img src="assets/images/logo-photo.png" alt="Hermoso Atelier logo" class="brand-logo">
        <span class="brand-copy">
            <span class="brand-name">HERMOSO ATELIER</span>
            <span class="brand-tagline">CRAFTING ELEGANCE, DEFINING YOU</span>
        </span>
    </a>
    <nav class="desktop-nav">
        <a href="index.php#home">HOME</a>
        <a href="index.php#services">SERVICES</a>
        <a href="index.php#gallery">GALLERY</a>
        <a href="book-appointment.php" class="nav-cta">BOOK AN APPOINTMENT</a>
    </nav>
</header>

<main class="dashboard-main">
    <div class="dashboard-head">
        <div>
            <div class="auth-kicker">MY ACCOUNT</div>
            <h1>WELCOME, <span><?= htmlspecialchars(strtoupper($_SESSION['full_name']), ENT_QUOTES, 'UTF-8') ?></span></h1>
            <p>Manage your Hermoso Atelier appointments from one place.</p>
        </div>
        <a href="logout.php" class="dashboard-link">LOG OUT</a>
    </div>

    <?php if ($message): ?>
        <div class="auth-message <?= $status === 'error' ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-actions">
        <a href="book-appointment.php" class="auth-button inline-button">BOOK AN APPOINTMENT</a>
    </div>

    <section class="appointment-panel">
        <h2>MY APPOINTMENTS</h2>

        <?php if (empty($appointments)): ?>
            <p class="empty-state">
                You do not have any appointments yet.
                <a href="book-appointment.php">Book your first consultation.</a>
            </p>
        <?php else: ?>
            <div class="appointment-table-wrap">
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>SERVICE</th>
                            <th>DATE</th>
                            <th>TIME</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['service_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_time'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(ucfirst($appointment['status']), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if (in_array($appointment['status'], ['pending', 'confirmed'], true)): ?>
                                    <form method="POST" action="appointment_function.php"
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="appointment_id" value="<?= (int)$appointment['id'] ?>">
                                        <button type="submit" class="auth-button inline-button">CANCEL</button>
                                    </form>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
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
