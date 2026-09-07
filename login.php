<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
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
    <meta name="description" content="Log in to Hermoso Atelier.">
    <title>Login | Hermoso Atelier</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="auth-page">

<header class="site-header" id="top">
    <a class="brand" href="index.php" aria-label="Hermoso Atelier home">
        <img src="assets/images/logo-photo.png"
             alt="Hermoso Atelier logo"
             class="brand-logo">
        <span class="brand-copy">
            <span class="brand-name">HERMOSO ATELIER</span>
            <span class="brand-tagline">CRAFTING ELEGANCE, DEFINING YOU</span>
        </span>
    </a>

    <nav class="desktop-nav" aria-label="Primary navigation">
        <a href="index.php#home">HOME</a>
        <a href="index.php#about">ABOUT US</a>
        <a href="index.php#services">SERVICES</a>
        <a href="index.php#gallery">GALLERY</a>
        <a href="index.php#contact">CONTACT US</a>
        <a href="login.php" class="nav-cta">BOOK A CONSULTATION</a>
    </nav>
</header>

<main class="auth-main">
    <section class="auth-card">
        <div class="auth-kicker">WELCOME BACK</div>
        <h1>LOGIN TO<br><span>YOUR ACCOUNT</span></h1>

        <div class="ornament">
            <span></span><b>✥</b><span></span>
        </div>

        <p class="auth-intro">
            Sign in to manage your appointments and continue your
            Hermoso Atelier experience.
        </p>

        <?php if ($status === 'error' && $message): ?>
            <div class="auth-message error">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($status === 'success' && $message): ?>
            <div class="auth-message success">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login_function.php" class="auth-form">
            <label for="email">EMAIL ADDRESS</label>
            <div class="input-wrap">
                <i class="fa-regular fa-envelope"></i>
                <input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <label for="password">PASSWORD</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock"></i>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    required
                >
                <button type="button" class="password-toggle"
                        aria-label="Show password"
                        data-target="password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="auth-button">LOGIN</button>
        </form>

        <p class="auth-switch">
            Don't have an account?
            <a href="register.php">CREATE AN ACCOUNT</a>
        </p>

        <a class="back-home" href="index.php">← Back to Hermoso Atelier</a>
    </section>

    <aside class="auth-image">
        <img src="assets/images/contact-photo.jpg" alt="Hermoso Atelier interior">
        <div class="auth-image-overlay">
            <p>“Where timeless style meets exceptional craftsmanship.”</p>
            <span>HERMOSO ATELIER</span>
        </div>
    </aside>
</main>

<script src="assets/js/auth.js"></script>
</body>
</html>
