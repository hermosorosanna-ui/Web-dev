
<?php

require 'database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = [];

if ($fullName === '') {
    $errors[] = 'Full name is required.';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}

if ($password === '' || strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}

if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    header('Location: register.php?status=error&message=' . urlencode(implode(' ', $errors)));
    exit;
}

try {
    $pdo = getConnection();

    $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $check->bindValue(':email', $email);
    $check->execute();

    if ($check->fetch()) {
        header('Location: register.php?status=error&message=' . urlencode('An account with that email already exists.'));
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, phone, password_hash)
            VALUES (:full_name, :email, :phone, :password_hash)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':full_name', $fullName);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone !== '' ? $phone : null, $phone !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':password_hash', $passwordHash);
    $stmt->execute();

    header('Location: login.php?status=success&message=' . urlencode('Account created successfully. Please log in.'));
    exit;

} catch (PDOException $e) {
    header('Location: register.php?status=error&message=' . urlencode('Unable to create the account right now.'));
    exit;
}
