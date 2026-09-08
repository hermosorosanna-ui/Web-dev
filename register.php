
<?php
// Registration page checks the normal customer session.
session_name('HERMOSO_SESSION');
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
    <title>Create Account | Hermoso Atelier</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="auth-page">

<header class="site-header">
    <a class="brand" href="index.php">
        <img src="assets/images/logo-photo.png" alt="Hermoso Atelier logo" class="brand-logo">
        <span class="brand-copy">
            <span class="brand-name">HERMOSO ATELIER</span>
            <span class="brand-tagline">CRAFTING ELEGANCE, DEFINING YOU</span>
        </span>
    </a>
    <nav class="desktop-nav">
        <a href="index.php#home">HOME</a>
        <a href="index.php#about">ABOUT US</a>
        <a href="index.php#services">SERVICES</a>
        <a href="index.php#gallery">GALLERY</a>
        <a href="index.php#contact">CONTACT US</a>
        <a href="login.php" class="nav-cta">BOOK A CONSULTATION</a>
    </nav>
</header>

<main class="auth-main register-layout">
    <section class="auth-card">
        <div class="auth-kicker">NEW CLIENT</div>
        <h1>CREATE<br><span>YOUR ACCOUNT</span></h1>

        <div class="ornament">
            <span></span><b>✥</b><span></span>
        </div>

        <p class="auth-intro">
            Register to book and manage appointments with Hermoso Atelier.
        </p>

        <?php if ($status === 'error' && $message): ?>
            <div class="auth-message error">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register_function.php" class="auth-form">
            <label for="full_name">FULL NAME</label>
            <div class="input-wrap">
                <i class="fa-regular fa-user"></i>
                <input id="full_name" type="text" name="full_name"
                       autocomplete="name" placeholder="Enter your full name" required>
            </div>

            <label for="register_email">EMAIL ADDRESS</label>
            <div class="input-wrap">
                <i class="fa-regular fa-envelope"></i>
                <input id="register_email" type="email" name="email"
                       autocomplete="email" placeholder="Enter your email" required>
            </div>

            <label for="phone">PHONE NUMBER</label>
            <div class="input-wrap">
                <i class="fa-solid fa-phone"></i>
                <input id="phone" type="tel" name="phone"
                       autocomplete="tel" placeholder="Enter your phone number">
            </div>

            <label for="register_password">PASSWORD</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock"></i>
                <input id="register_password" type="password" name="password"
                       autocomplete="new-password" placeholder="Create a password" required>
                <button type="button" class="password-toggle"
                        aria-label="Show password"
                        data-target="register_password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <label for="confirm_password">CONFIRM PASSWORD</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock"></i>
                <input id="confirm_password" type="password" name="confirm_password"
                       autocomplete="new-password" placeholder="Repeat your password" required>
                <button type="button" class="password-toggle"
                        aria-label="Show password"
                        data-target="confirm_password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="auth-button">CREATE ACCOUNT</button>
        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">LOGIN</a>
        </p>

        <a class="back-home" href="index.php">← Back to Hermoso Atelier</a>
    </section>

    <aside class="auth-image">
        <img src="assets/images/hero-photo.jpg" alt="Hermoso Atelier fashion">
        <div class="auth-image-overlay">
            <p>“Crafting elegance, defining you.”</p>
            <span>HERMOSO ATELIER</span>
        </div>
    </aside>
</main>

<script src="assets/js/auth.js"></script>
</body>
</html>

