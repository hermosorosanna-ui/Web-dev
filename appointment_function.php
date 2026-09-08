<?php

// Appointment requests and customer appointment cancellation use the same customer session.
session_name('HERMOSO_SESSION');
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'customer') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: book-appointment.php');
    exit;
}

require 'database/config.php';

$action = $_POST['action'] ?? '';

// Customer cancellation: only the logged-in customer's own pending/confirmed
// appointment can be cancelled. The appointment is kept in the database and
// its status is changed to 'cancelled' rather than being deleted.
if ($action === 'cancel') {
    $appointmentId = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);

    if (!$appointmentId) {
        header('Location: dashboard.php?status=error&message=' . urlencode('Invalid appointment.'));
        exit;
    }

    try {
        $pdo = getConnection();

        $stmt = $pdo->prepare(
            "UPDATE appointments
             SET status = 'cancelled'
             WHERE id = :id
               AND user_id = :user_id
               AND status IN ('pending', 'confirmed')"
        );

        $stmt->execute([
            ':id' => $appointmentId,
            ':user_id' => $_SESSION['user_id']
        ]);

        if ($stmt->rowCount() === 0) {
            header('Location: dashboard.php?status=error&message=' . urlencode('Appointment could not be cancelled. It may not belong to you or may no longer be cancellable.'));
            exit;
        }

        header('Location: dashboard.php?status=success&message=' . urlencode('Appointment cancelled successfully.'));
        exit;
    } catch (PDOException $e) {
        header('Location: dashboard.php?status=error&message=' . urlencode('Unable to cancel the appointment right now.'));
        exit;
    }
}

// Existing appointment-request flow.
$serviceId = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
$date = trim($_POST['appointment_date'] ?? '');
$time = trim($_POST['appointment_time'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if (!$serviceId || $date === '' || $time === '') {
    header('Location: book-appointment.php?status=error&message=' . urlencode('Please complete all appointment fields.'));
    exit;
}

try {
    $pdo = getConnection();

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
        header('Location: book-appointment.php?status=error&message=' . urlencode('That appointment time is already unavailable.'));
        exit;
    }

    $sql = "INSERT INTO appointments
            (user_id, service_id, appointment_date, appointment_time, notes)
            VALUES (:user_id, :service_id, :appointment_date, :appointment_time, :notes)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':service_id' => $serviceId,
        ':appointment_date' => $date,
        ':appointment_time' => $time,
        ':notes' => $notes !== '' ? $notes : null
    ]);

    header('Location: dashboard.php?status=success');
    exit;

} catch (PDOException $e) {
    header('Location: book-appointment.php?status=error&message=' . urlencode('Unable to request the appointment right now.'));
    exit;
}
