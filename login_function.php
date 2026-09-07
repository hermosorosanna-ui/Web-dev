<?php

session_start();

require 'database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?status=error&message=' . urlencode('Please enter a valid email address.'));
    exit;
}

if ($password === '') {
    header('Location: login.php?status=error&message=' . urlencode('Password is required.'));
    exit;
}

try {
    $pdo = getConnection();

    $sql = "SELECT id, full_name, email, password_hash, role
            FROM users
            WHERE email = :email
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        header('Location: login.php?status=error&message=' . urlencode('Incorrect email or password.'));
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    header('Location: dashboard.php');
    exit;

} catch (PDOException $e) {
    header('Location: login.php?status=error&message=' . urlencode('Unable to process login right now.'));
    exit;
}
