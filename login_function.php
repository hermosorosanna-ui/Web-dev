
<?php
// Normal login for BOTH customers and admins.
// Customers use HERMOSO_SESSION; admins use HERMOSO_ADMIN_SESSION.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

require 'database/config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: login.php?status=error&message=' . urlencode('Please enter your email and password.'));
    exit;
}

try {
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        "SELECT id, full_name, email, password_hash, role
         FROM users
         WHERE email = :email
         LIMIT 1"
    );
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        header('Location: login.php?status=error&message=' . urlencode('Invalid email or password.'));
        exit;
    }

    $role = $user['role'];

    if ($role === 'admin') {
        // Admin gets an isolated session so an admin login does not overwrite
        // a customer session in another browser tab.
        session_name('HERMOSO_ADMIN_SESSION');
        session_start();
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'admin';

        header('Location: admin/dashboard.php');
        exit;
    }

    if ($role === 'customer') {
        // Customer uses the normal website session.
        session_name('HERMOSO_SESSION');
        session_start();
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'customer';

        header('Location: dashboard.php');
        exit;
    }

    header('Location: login.php?status=error&message=' . urlencode('This account has an invalid role.'));
    exit;
} catch (PDOException $e) {
    header('Location: login.php?status=error&message=' . urlencode('Unable to process login.'));
    exit;
}

