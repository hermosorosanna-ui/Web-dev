<?php
// All admin pages use the same dedicated session created by the root login.
session_name('HERMOSO_ADMIN_SESSION');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}