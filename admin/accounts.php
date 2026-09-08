<?php
require 'auth_check.php';
require '../database/config.php';
$pdo = getConnection();

$stmt = $pdo->query(
    "SELECT id, full_name, email, phone, role, created_at
     FROM users
     ORDER BY created_at DESC, id DESC"
);
$accounts = $stmt->fetchAll();
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accounts | Hermoso Atelier Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-page">
<?php include 'header.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <div class="admin-kicker">ACCOUNT MANAGEMENT</div>
            <h1>MANAGE <span>ACCOUNTS</span></h1>
            <p>Create, view, edit, and delete customer and administrator accounts.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="admin-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="admin-panel">
        <div class="panel-heading">
            <h2>ALL ACCOUNTS</h2>
            <a href="account_create.php">+ ADD ACCOUNT</a>
        </div>

        <?php if (!$accounts): ?>
            <p class="empty-state">No accounts found.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table accounts-table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>ROLE</th>
                            <th>REGISTERED</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($account['full_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php if ((int)$account['id'] === (int)$_SESSION['user_id']): ?>
                                    <br><small class="current-account">CURRENT ACCOUNT</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($account['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $account['phone'] ? htmlspecialchars($account['phone'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
                            <td>
                                <span class="status-badge <?= $account['role'] === 'admin' ? 'status-confirmed' : 'status-pending' ?>">
                                    <?= ucfirst(htmlspecialchars($account['role'], ENT_QUOTES, 'UTF-8')) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(date('M j, Y', strtotime($account['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="account-actions">
                                    <a href="account_edit.php?id=<?= (int)$account['id'] ?>" class="admin-outline-button">EDIT</a>
                                    <?php if ((int)$account['id'] !== (int)$_SESSION['user_id']): ?>
                                        <form method="POST" action="account_action.php" onsubmit="return confirm('Are you sure you want to delete this account? This will also delete its appointments.');">
                                            <input type="hidden" name="account_id" value="<?= (int)$account['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="admin-delete-button">DELETE</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="account-protected">PROTECTED</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
