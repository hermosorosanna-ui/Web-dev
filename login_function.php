<<<<<<< HEAD

<?php
// Normal login for BOTH customers and admins.
// Customers use HERMOSO_SESSION; admins use HERMOSO_ADMIN_SESSION.
=======
<?php

session_start();

require 'database/config.php';
>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

<<<<<<< HEAD
require 'database/config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: login.php?status=error&message=' . urlencode('Please enter your email and password.'));
=======
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?status=error&message=' . urlencode('Please enter a valid email address.'));
    exit;
}

if ($password === '') {
    header('Location: login.php?status=error&message=' . urlencode('Password is required.'));
>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec
    exit;
}

try {
    $pdo = getConnection();

<<<<<<< HEAD
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

=======
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
>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec
