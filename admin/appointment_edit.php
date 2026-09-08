<?php
require 'auth_check.php';
require 'constants.php';
require '../database/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$pdo = getConnection();

$stmt = $pdo->prepare(
    "SELECT id, user_id, service_id, appointment_date, appointment_time, notes, status
     FROM appointments WHERE id = :id LIMIT 1"
);
$stmt->execute([':id' => $id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    header('Location: dashboard.php');
    exit;
}

$customers = $pdo->query("SELECT id, full_name, email FROM users WHERE role = 'customer' ORDER BY full_name ASC")->fetchAll();
$services = $pdo->query("SELECT id, name FROM services ORDER BY name ASC")->fetchAll();
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Appointment | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">APPOINTMENT MANAGEMENT</div>
            <h1>EDIT <span>APPOINTMENT</span></h1>
            <p>Update customer, schedule, service, notes, or status.</p>
        </div>
    </div>

    <?php if ($status === 'error' && $message): ?>
        <div class="admin-message error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="admin-panel">
        <form method="POST" action="appointment_action.php" class="admin-form">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="appointment_id" value="<?= (int)$appointment['id'] ?>">

            <label for="user_id">CUSTOMER</label>
            <select id="user_id" name="user_id" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= (int)$customer['id'] ?>"
                        <?= (int)$appointment['user_id'] === (int)$customer['id'] ? 'selected' : '' ?> >
                        <?= htmlspecialchars($customer['full_name'], ENT_QUOTES, 'UTF-8') ?> -
                        <?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="service_id">SERVICE</label>
            <select id="service_id" name="service_id" required>
                <?php foreach ($services as $service): ?>
                    <option value="<?= (int)$service['id'] ?>"
                        <?= (int)$appointment['service_id'] === (int)$service['id'] ? 'selected' : '' ?> >
                        <?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="appointment_date">DATE</label>
            <input id="appointment_date" type="date" name="appointment_date"
                   value="<?= htmlspecialchars($appointment['appointment_date'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="appointment_time">TIME</label>
            <input id="appointment_time" type="time" name="appointment_time"
                   value="<?= htmlspecialchars(substr($appointment['appointment_time'], 0, 5), ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="status">STATUS</label>
            <select id="status" name="status" required>
                <?php foreach (ADMIN_APPOINTMENT_STATUSES as $appointmentStatus): ?>
                    <option value="<?= htmlspecialchars($appointmentStatus, ENT_QUOTES, 'UTF-8') ?>"
                        <?= $appointment['status'] === $appointmentStatus ? 'selected' : '' ?> >
                        <?= ucfirst($appointmentStatus) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="notes">NOTES</label>
            <textarea id="notes" name="notes" rows="6" placeholder="Additional appointment notes..."><?= htmlspecialchars($appointment['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

            <button type="submit" class="admin-button">SAVE CHANGES</button>
        </form>
    </section>
</main>
</body>
</html>
