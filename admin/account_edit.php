<?php
require 'auth_check.php';
require '../database/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: accounts.php');
    exit;
}

$pdo = getConnection();

$stmt = $pdo->prepare(
    "SELECT id, full_name, email, phone, role
     FROM users
     WHERE id = :id
     LIMIT 1"
);
$stmt->execute([':id' => $id]);
$account = $stmt->fetch();

if (!$account) {
    header('Location: accounts.php?message=' . urlencode('Account not found.'));
    exit;
}

$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Account | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">ACCOUNT MANAGEMENT</div>
            <h1>EDIT <span>ACCOUNT</span></h1>
            <p>Update account information and permissions.</p>
        </div>
    </div>

    <?php if ($status === 'error' && $message): ?>
        <div class="admin-message error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="admin-panel account-form-panel">
        <form method="POST" action="account_action.php" class="admin-form">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="account_id" value="<?= (int)$account['id'] ?>">

            <label for="full_name">FULL NAME</label>
            <input id="full_name" type="text" name="full_name" maxlength="120" value="<?= htmlspecialchars($account['full_name'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="email">EMAIL ADDRESS</label>
            <input id="email" type="email" name="email" maxlength="190" value="<?= htmlspecialchars($account['email'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="phone">PHONE NUMBER</label>
            <input id="phone" type="tel" name="phone" maxlength="30" value="<?= htmlspecialchars($account['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label for="role">ROLE</label>
            <select id="role" name="role" required>
                <option value="customer" <?= $account['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="admin" <?= $account['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <label for="password">NEW PASSWORD</label>
            <input id="password" type="password" name="password" minlength="8" placeholder="Leave blank to keep current password">

            <button type="submit" class="admin-button">SAVE CHANGES</button>
        </form>
    </section>
</main>
</body>
</html>
