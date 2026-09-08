<?php
require 'auth_check.php';
require '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: accounts.php');
    exit;
}

$action = $_POST['action'] ?? '';
$pdo = getConnection();

if ($action === 'create') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? 'customer';
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['customer', 'admin'], true) || strlen($password) < 8) {
        header('Location: account_create.php?status=error&message=' . urlencode('Enter a valid name, email, role, and password of at least 8 characters.'));
        exit;
    }

    $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        header('Location: account_create.php?status=error&message=' . urlencode('An account with that email already exists.'));
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO users (full_name, email, phone, password_hash, role)
         VALUES (:full_name, :email, :phone, :password_hash, :role)"
    );

    $stmt->execute([
        ':full_name' => $fullName,
        ':email' => $email,
        ':phone' => $phone !== '' ? $phone : null,
        ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ':role' => $role
    ]);

    header('Location: accounts.php?message=' . urlencode('Account created successfully.'));
    exit;
}

if ($action === 'update') {
    $id = filter_input(INPUT_POST, 'account_id', FILTER_VALIDATE_INT);
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$id || $fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['customer', 'admin'], true)) {
        header('Location: accounts.php?message=' . urlencode('Invalid account information.'));
        exit;
    }

    $accountCheck = $pdo->prepare('SELECT id, role FROM users WHERE id = :id LIMIT 1');
    $accountCheck->execute([':id' => $id]);
    $account = $accountCheck->fetch();

    if (!$account) {
        header('Location: accounts.php?message=' . urlencode('Account not found.'));
        exit;
    }

    // The current admin may edit their own details, but may not remove their own admin access.
    if ((int)$id === (int)$_SESSION['user_id'] && $role !== 'admin') {
        header('Location: account_edit.php?id=' . $id . '&status=error&message=' . urlencode('Your current account must remain an administrator while you are logged in.'));
        exit;
    }

    $emailCheck = $pdo->prepare(
        'SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1'
    );
    $emailCheck->execute([
        ':email' => $email,
        ':id' => $id
    ]);

    if ($emailCheck->fetch()) {
        header('Location: account_edit.php?id=' . $id . '&status=error&message=' . urlencode('Another account already uses that email address.'));
        exit;
    }

    if ($password !== '') {
        if (strlen($password) < 8) {
            header('Location: account_edit.php?id=' . $id . '&status=error&message=' . urlencode('New password must be at least 8 characters.'));
            exit;
        }

        $stmt = $pdo->prepare(
            "UPDATE users
             SET full_name = :full_name,
                 email = :email,
                 phone = :phone,
                 role = :role,
                 password_hash = :password_hash
             WHERE id = :id"
        );
        $stmt->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':phone' => $phone !== '' ? $phone : null,
            ':role' => $role,
            ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare(
            "UPDATE users
             SET full_name = :full_name,
                 email = :email,
                 phone = :phone,
                 role = :role
             WHERE id = :id"
        );
        $stmt->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':phone' => $phone !== '' ? $phone : null,
            ':role' => $role,
            ':id' => $id
        ]);
    }

    // Refresh the current session when the logged-in admin edits their own account.
    if ((int)$id === (int)$_SESSION['user_id']) {
        $_SESSION['full_name'] = $fullName;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin';
    }

    header('Location: accounts.php?message=' . urlencode('Account updated successfully.'));
    exit;
}

if ($action === 'delete') {
    $id = filter_input(INPUT_POST, 'account_id', FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: accounts.php?message=' . urlencode('Invalid account.'));
        exit;
    }

    if ((int)$id === (int)$_SESSION['user_id']) {
        header('Location: accounts.php?message=' . urlencode('You cannot delete the account you are currently using.'));
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        header('Location: accounts.php?message=' . urlencode('Account not found.'));
        exit;
    }

    header('Location: accounts.php?message=' . urlencode('Account deleted successfully.'));
    exit;
}

header('Location: accounts.php');
exit;
