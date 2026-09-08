<?php
require '../database/config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $message = 'Enter a name, valid email, and password of at least 8 characters.';
    } else {
        try {
            $pdo = getConnection();
            $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $check->execute([':email' => $email]);

            if ($check->fetch()) {
                $message = 'An account with that email already exists.';
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO users (full_name, email, password_hash, role)
                     VALUES (:full_name, :email, :password_hash, 'admin')"
                );
                $stmt->execute([
                    ':full_name' => $fullName,
                    ':email' => $email,
                    ':password_hash' => password_hash($password, PASSWORD_DEFAULT)
                ]);
                $message = 'Admin account created. Delete create_admin.php now.';
            }
        } catch (PDOException $e) {
            $message = 'Unable to create the admin account.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Admin | Hermoso Atelier</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-auth-page">
<main class="admin-auth-card">
    <div class="admin-kicker">HERMOSO ATELIER</div>
    <h1>CREATE<br><span>ADMIN ACCOUNT</span></h1>
    <?php if ($message): ?><div class="admin-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <form method="POST" class="admin-form">
        <label>FULL NAME</label>
        <input type="text" name="full_name" required>
        <label>EMAIL ADDRESS</label>
        <input type="email" name="email" required>
        <label>PASSWORD</label>
        <input type="password" name="password" minlength="8" required>
        <button class="admin-button" type="submit">CREATE ADMIN</button>
    </form>
</main>
</body>
</html>