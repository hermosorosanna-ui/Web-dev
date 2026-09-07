<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'database/config.php';
$pdo = getConnection();

$stmt = $pdo->query("SELECT id, name FROM services WHERE is_active = 1 ORDER BY id ASC");
$services = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment | Hermoso Atelier</title>
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
        <a href="dashboard.php">MY ACCOUNT</a>
        <a href="logout.php" class="nav-cta">LOG OUT</a>
    </nav>
</header>

<main class="dashboard-main">
    <div class="dashboard-head">
        <div>
            <div class="auth-kicker">HERMOSO ATELIER</div>
            <h1>BOOK AN <span>APPOINTMENT</span></h1>
            <p>Select a service. The calendar and available time slots can be connected next.</p>
        </div>
    </div>

    <section class="appointment-panel booking-panel">
        <form method="POST" action="appointment_function.php" class="auth-form">
            <label for="service_id">SELECT SERVICE</label>
            <select id="service_id" name="service_id" required>
                <option value="">Choose a service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars($service['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="appointment_date">SELECT DATE</label>
            <input id="appointment_date" type="date" name="appointment_date" required>

            <label for="appointment_time">SELECT TIME</label>
            <input id="appointment_time" type="time" name="appointment_time" required>

            <label for="notes">ADDITIONAL NOTES</label>
            <textarea id="notes" name="notes" rows="5"
                      placeholder="Tell us anything we should know before your appointment."></textarea>

            <button type="submit" class="auth-button">REQUEST APPOINTMENT</button>
        </form>
    </section>
</main>
</body>
</html>
