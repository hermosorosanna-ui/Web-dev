<?php
require 'auth_check.php';
require 'constants.php';
require '../database/config.php';

$pdo = getConnection();
$customers = $pdo->query("SELECT id, full_name, email FROM users WHERE role = 'customer' ORDER BY full_name ASC")->fetchAll();
$services = $pdo->query("SELECT id, name FROM services WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Appointment | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">APPOINTMENT MANAGEMENT</div>
            <h1>ADD <span>APPOINTMENT</span></h1>
            <p>Create a new appointment for a customer.</p>
        </div>
    </div>

    <?php if ($status === 'error' && $message): ?>
        <div class="admin-message error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="admin-panel">
        <form method="POST" action="appointment_action.php" class="admin-form">
            <input type="hidden" name="action" value="create">

            <label for="user_id">CUSTOMER</label>
            <select id="user_id" name="user_id" required>
                <option value="">Select customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= (int)$customer['id'] ?>">
                        <?= htmlspecialchars($customer['full_name'], ENT_QUOTES, 'UTF-8') ?> -
                        <?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="service_id">SERVICE</label>
            <select id="service_id" name="service_id" required>
                <option value="">Select service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= (int)$service['id'] ?>">
                        <?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="appointment_date">DATE</label>
            <input id="appointment_date" type="date" name="appointment_date" required>

            <label for="appointment_time">TIME</label>
            <input id="appointment_time" type="time" name="appointment_time" required>

            <label for="status">STATUS</label>
            <select id="status" name="status" required>
                <?php foreach (ADMIN_APPOINTMENT_STATUSES as $appointmentStatus): ?>
                    <option value="<?= htmlspecialchars($appointmentStatus, ENT_QUOTES, 'UTF-8') ?>"
                        <?= $appointmentStatus === 'pending' ? 'selected' : '' ?> >
                        <?= ucfirst($appointmentStatus) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="notes">NOTES</label>
            <textarea id="notes" name="notes" rows="6" placeholder="Additional appointment notes..."></textarea>

            <button type="submit" class="admin-button">CREATE APPOINTMENT</button>
        </form>
    </section>
</main>
</body>
</html>
