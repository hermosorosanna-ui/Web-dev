<?php
require 'auth_check.php';
require '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: services.php');
    exit;
}

$id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$id || !in_array($action, ['activate','deactivate'], true)) {
    header('Location: services.php');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("UPDATE services SET is_active=:active WHERE id=:id");
$stmt->execute([':active'=>$action==='activate'?1:0, ':id'=>$id]);

header('Location: services.php?message=' . urlencode('Service updated successfully.'));
exit;