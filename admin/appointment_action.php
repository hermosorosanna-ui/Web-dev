<?php

require 'auth_check.php';
require 'constants.php';
require '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$action = $_POST['action'] ?? '';
$pdo = getConnection();

if ($action === 'create') {
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $serviceId = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $status = $_POST['status'] ?? 'pending';

    if (!$userId || !$serviceId || $date === '' || $time === '' || !in_array($status, ADMIN_APPOINTMENT_STATUSES, true)) {
        header('Location: appointment_create.php?status=error&message=' . urlencode('Please complete all appointment fields.'));
        exit;
    }

    $userCheck = $pdo->prepare("SELECT id FROM users WHERE id = :id AND role = 'customer' LIMIT 1");
    $userCheck->execute([':id' => $userId]);
    if (!$userCheck->fetch()) {
        header('Location: appointment_create.php?status=error&message=' . urlencode('Invalid customer selected.'));
        exit;
    }

    $serviceCheck = $pdo->prepare("SELECT id FROM services WHERE id = :id LIMIT 1");
    $serviceCheck->execute([':id' => $serviceId]);
    if (!$serviceCheck->fetch()) {
        header('Location: appointment_create.php?status=error&message=' . urlencode('Invalid service selected.'));
        exit;
    }

    $check = $pdo->prepare(
        "SELECT id FROM appointments
         WHERE appointment_date = :appointment_date
           AND appointment_time = :appointment_time
           AND status IN ('pending', 'confirmed')
         LIMIT 1"
    );
    $check->execute([
        ':appointment_date' => $date,
        ':appointment_time' => $time
    ]);

    if ($check->fetch()) {
        header('Location: appointment_create.php?status=error&message=' . urlencode('That appointment time is already unavailable.'));
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO appointments
        (user_id, service_id, appointment_date, appointment_time, notes, status)
        VALUES (:user_id, :service_id, :appointment_date, :appointment_time, :notes, :status)"
    );
    $stmt->execute([
        ':user_id' => $userId,
        ':service_id' => $serviceId,
        ':appointment_date' => $date,
        ':appointment_time' => $time,
        ':notes' => $notes !== '' ? $notes : null,
        ':status' => $status
    ]);

    header('Location: dashboard.php?message=' . urlencode('Appointment created successfully.'));
    exit;
}

if ($action === 'update') {
    $id = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $serviceId = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $status = $_POST['status'] ?? '';

    if (!$id || !$userId || !$serviceId || $date === '' || $time === '' || !in_array($status, ADMIN_APPOINTMENT_STATUSES, true)) {
        header('Location: dashboard.php?message=' . urlencode('Invalid appointment information.'));
        exit;
    }

    $appointmentCheck = $pdo->prepare("SELECT id FROM appointments WHERE id = :id LIMIT 1");
    $appointmentCheck->execute([':id' => $id]);
    if (!$appointmentCheck->fetch()) {
        header('Location: dashboard.php?message=' . urlencode('Appointment not found.'));
        exit;
    }

    $userCheck = $pdo->prepare("SELECT id FROM users WHERE id = :id AND role = 'customer' LIMIT 1");
    $userCheck->execute([':id' => $userId]);
    if (!$userCheck->fetch()) {
        header('Location: dashboard.php?message=' . urlencode('Invalid customer selected.'));
        exit;
    }

    $serviceCheck = $pdo->prepare("SELECT id FROM services WHERE id = :id LIMIT 1");
    $serviceCheck->execute([':id' => $serviceId]);
    if (!$serviceCheck->fetch()) {
        header('Location: dashboard.php?message=' . urlencode('Invalid service selected.'));
        exit;
    }

    $check = $pdo->prepare(
        "SELECT id FROM appointments
         WHERE appointment_date = :appointment_date
           AND appointment_time = :appointment_time
           AND status IN ('pending', 'confirmed')
           AND id != :id
         LIMIT 1"
    );
    $check->execute([
        ':appointment_date' => $date,
        ':appointment_time' => $time,
        ':id' => $id
    ]);

    if ($check->fetch()) {
        header('Location: appointment_edit.php?id=' . $id . '&status=error&message=' . urlencode('That appointment time is already unavailable.'));
        exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE appointments
         SET user_id = :user_id,
             service_id = :service_id,
             appointment_date = :appointment_date,
             appointment_time = :appointment_time,
             notes = :notes,
             status = :status
         WHERE id = :id"
    );
    $stmt->execute([
        ':user_id' => $userId,
        ':service_id' => $serviceId,
        ':appointment_date' => $date,
        ':appointment_time' => $time,
        ':notes' => $notes !== '' ? $notes : null,
        ':status' => $status,
        ':id' => $id
    ]);

    header('Location: dashboard.php?message=' . urlencode('Appointment updated successfully.'));
    exit;
}

if ($action === 'delete') {
    $id = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: dashboard.php?message=' . urlencode('Invalid appointment.'));
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header('Location: dashboard.php?message=' . urlencode('Appointment deleted successfully.'));
    exit;
}

header('Location: dashboard.php');
exit;
