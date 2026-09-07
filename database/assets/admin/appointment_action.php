<?php
require 'auth_check.php';
require '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: appointments.php');
    exit;
}

$id = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
$allowed = ['pending','confirmed','completed','cancelled'];

if (!$id || !in_array($status, $allowed, true)) {
    header('Location: appointments.php?status=all');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("UPDATE appointments SET status=:status WHERE id=:id");
$stmt->execute([':status'=>$status, ':id'=>$id]);

header('Location: appointments.php?status=' . urlencode($status));
exit;
