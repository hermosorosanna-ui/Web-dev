<?php
require 'auth_check.php';
require '../database/config.php';
require 'constants.php';

$pdo = getConnection();
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Account | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">ACCOUNT MANAGEMENT</div>
            <h1>ADD <span>ACCOUNT</span></h1>
            <p>Create a new customer or administrator account.</p>
        </div>
    </div>

    <?php if ($status === 'error' && $message): ?>
        <div class="admin-message error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="admin-panel account-form-panel">
        <form method="POST" action="account_action.php" class="admin-form">
            <input type="hidden" name="action" value="create">

            <label for="full_name">FULL NAME</label>
            <input id="full_name" type="text" name="full_name" maxlength="120" required>

            <label for="email">EMAIL ADDRESS</label>
            <input id="email" type="email" name="email" maxlength="190" required>

            <label for="phone">PHONE NUMBER</label>
            <input id="phone" type="tel" name="phone" maxlength="30">

            <label for="role">ROLE</label>
            <select id="role" name="role" required>
                <option value="customer" selected>Customer</option>
                <option value="admin">Admin</option>
            </select>

            <label for="password">PASSWORD</label>
            <input id="password" type="password" name="password" minlength="8" required>

            <button type="submit" class="admin-button">CREATE ACCOUNT</button>
        </form>
    </section>
</main>
</body>
</html>
