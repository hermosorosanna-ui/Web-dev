<?php
$pageTitle = 'Hermoso Atelier';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hermoso Atelier - Crafting elegance, defining you.">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
 <link rel="stylesheet" href="assets/css/style.css">
 <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>
 </head>
<body>
  <header class="site-header" id="top">
    <a class="brand" href="#home" aria-label="Hermoso Atelier home">
      <img src="assets/images/logo-photo.png" alt="Hermoso Atelier logo photo" class="brand-logo">
      <span class="brand-copy">
        <span class="brand-name">HERMOSO ATELIER</span>
        <span class="brand-tagline">CRAFTING ELEGANCE, DEFINING YOU</span>
      </span>
    </a>

    <nav class="desktop-nav" aria-label="Primary navigation">
      <a href="#home">HOME</a>
      <a href="#about">ABOUT US</a>
      <a href="#services">SERVICES</a>
      <a href="#gallery">GALLERY</a>
      <a href="#contact">CONTACT US</a>
      <a href="#contact" class="nav-cta">BOOK A CONSULTATION</a>
    </nav>

    <button class="nav-toggle" aria-label="Open navigation" aria-expanded="false" aria-controls="mobile-nav">☰</button>
  </header>

  <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile navigation">
    <a href="#home">HOME</a>
    <a href="#about">ABOUT US</a>
    <a href="#services">SERVICES</a>
    <a href="#gallery">GALLERY</a>
    <a href="#contact">CONTACT US</a>
    <a href="#contact" class="nav-cta">BOOK A CONSULTATION</a>
  </nav>

  <main>
    <section class="screen home-screen" id="home">
      <div class="hero-copy">
        <img class="hero-copy-background" src="assets/images/hero-background-photo.jpg" alt="Hero text-area background placeholder">
        <div class="eyebrow">WELCOME TO</div>
        <h1>HERMOSO<br><span>ATELIER</span></h1>
        <div class="ornament"><span></span><b>✥</b><span></span></div>
        <p class="hero-quote">Where timeless style meets<br>exceptional craftsmanship.</p>
        <p class="hero-body">We create bespoke designs that reflect your unique<br class="desktop-only"> personality and bring your vision to life.</p>
        <div class="button-row">
          <a href="#contact" class="button filled">BOOK A CONSULTATION</a>
          <a href="#gallery" class="button outline">EXPLORE COLLECTION</a>
        </div>
      </div>
      <div class="hero-image image-frame hero-image-frame">
        <img src="assets/images/hero-photo.jpg" alt="Hero image photo">
      </div>
    </section>

    <section class="feature-strip" aria-label="Atelier highlights">
      <article class="feature-item">
        <img src="assets/images/icon-tailor-photo.png" alt="Tailoring icon photo">
        <div><h3>BESPOKE DESIGN</h3><p>Custom-made creations tailored<br>to your style and measurements.</p></div>
      </article>
      <article class="feature-item">
        <img src="assets/images/icon-sewing-photo.png" alt="Sewing icon photo">
        <div><h3>EXPERT CRAFTSMAN</h3><p>Precision, quality, and attention<br>to detail in every piece we create.</p></div>
      </article>
      <article class="feature-item">
        <img src="assets/images/icon-needle-photo.png" alt="Needle icon photo">
        <div><h3>TIMELESS ELEGANCE</h3><p>Sophisticated designs that<br>celebrate your individuality.</p></div>
      </article>
    </section>

    <section class="screen about-screen" id="about">
      <div class="about-copy">
        <div class="section-kicker">ABOUT US</div>
        <h2>FASHION MEETS<br><span>PRECISION</span></h2>
        <div class="ornament"><span></span><b>✥</b><span></span></div>
        <p>At Hermoso Atelier, we believe that every piece tells a story. Founded on a passion for timeless design and fine craftsmanship, we create bespoke garments that celebrate individuality and elevate confidence.</p>
        <p>From the first sketch to the final stitch, we are dedicated to quality, detail, and making your vision come to life.</p>
      </div>

      <div class="about-collage">
        <div class="image-frame about-large"><img src="assets/images/about-main-photo.svg" alt="About main image photo"></div>
        <div class="about-stack">
          <div class="image-frame"><img src="assets/images/about-small-1-photo.png" alt="About detail photo"></div>
          <div class="image-frame"><img src="assets/images/about-small-2-photo.png" alt="About detail photo"></div>
        </div>
        <div class="image-frame about-side"><img src="assets/images/about-side-photo.svg" alt="About side image photo"></div>
      </div>
    </section>

    <section class="feature-strip about-features" aria-label="Our mission, vision and values">
      <article class="feature-item">
        <img src="assets/images/icon-tailor-photo.png" alt="Mission icon photo">
        <div><h3>OUR MISSION</h3><div class="mini-rule"></div><p>To craft exceptional designs that<br>empower and inspire, blending<br>creativity with impeccable<br>craftsmanship.</p></div>
      </article>
      <article class="feature-item">
        <img src="assets/images/icon-sewing-photo.png" alt="Vision icon photo">
        <div><h3>OUR VISION</h3><div class="mini-rule"></div><p>To be a leading fashion atelier<br>known for timeless elegance,<br>innovation, and personalized<br>service.</p></div>
      </article>
      <article class="feature-item">
        <img src="assets/images/icon-needle-photo.png" alt="Values icon photo">
        <div><h3>OUR VALUES</h3><div class="mini-rule"></div><p>Quality in every detail<br>Integrity and honesty<br>Creativity and innovation<br>Client satisfaction</p></div>
      </article>
    </section>

    <section class="screen services-screen" id="services">
      <div class="services-head">
        <div class="section-kicker">OUR SERVICES</div>
        <h2>TAILORED TO <span>PERCEPTION</span></h2>
        <p>From concept to creation, we offer a range of specialized services<br>designed to bring your unique vision to life with elegance and precision.</p>
      </div>
      <div class="services-grid">
        <?php
        $services = [
          ['CUSTOM TAILORING','Bespoke designs tailored to<br>your measurements, style,<br>and personality','service-1-photo.svg'],
          ['ALTERATIONS AND REPAIRS','Professional alterations<br>and repairs to ensure the<br>perfect fit and finish.','service-2-photo.svg'],
          ['FASHION CONSULTATION','Personalized style advice<br>and design guidance to<br>create your signature look.','service-3-photo.svg'],
          ['BRIDAL AND FORMAL WEAR','Exquisite bridal and<br>evening wear for life\'s<br>most memorable moments.','service-4-photo.svg'],
          ['READY TO WEAR COLLECTION','Curated collections that<br>blend timeless elegance<br>with modern sophistication.','service-5-photo.svg']
        ];
        foreach ($services as [$title, $desc, $img]) : ?>
          <article class="service-card">
            <div class="image-frame"><img src="assets/images/<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> photo"></div>
            <h3><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
            <p><?= $desc ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="services-footer"><img src="assets/images/icon-tailor-photo.png" alt="Decorative tailoring icon photo"><em>Every piece we create is a blend of artistry, craftsmanship, and attention to detail.</em></div>
    </section>

    <section class="screen gallery-screen" id="gallery">
      <div class="gallery-head">
        <div class="section-kicker">OUR GALLERY</div>
        <h2>CREATIONS THAT INSPIRED</h2>
        <p>A showcase of our finest work where creativity, craftsmanship<br>and attention to detail come together in every piece.</p>
      </div>
      <div class="gallery-grid">
        <?php
        $gallery = [
          ['gallery-1-photo.svg','g1'],['gallery-2-photo.svg','g2'],['gallery-3-photo.svg','g3'],['gallery-4-photo.svg','g4'],['gallery-5-photo.svg','g5'],
          ['gallery-6-photo.svg','g6'],['gallery-7-photo.svg','g7'],['gallery-8-photo.svg','g8'],['gallery-9-photo.svg','g9'],['gallery-10-photo.svg','g10'],['gallery-11-photo.svg','g11']
        ];
        foreach ($gallery as [$img, $cls]) : ?>
          <div class="image-frame gallery-tile <?= htmlspecialchars($cls, ENT_QUOTES, 'UTF-8') ?>"><img src="assets/images/<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="Gallery photo"></div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="screen contact-screen" id="contact">
      <div class="contact-copy">
        <div class="section-kicker">CONTACT US</div>
        <h2>LET'S CREATE<br><span>Something Beautiful</span></h2>
        <p class="contact-lead">We would love to hear from you.<br>Whether you have a question, need more<br>information, or want to book a consultation,<br>our team is here to assist you.</p>
        <div class="contact-details">
          <div class="contact-detail">
            <div class="contact-icon">⌖</div>
            <div><h3>VISIT OUR ATELIER</h3><p>123 Elegance Avenue<br>Fashion District, City<br>Country 1000</p></div>
          </div>
          <div class="contact-detail">
            <div class="contact-icon">☎</div>
            <div><h3>CALL US</h3><p>+63 912 345 6789<br>(02) 8123 4567</p></div>
          </div>
          <div class="contact-detail">
            <div class="contact-icon">✉</div>
            <div><h3>EMAIL US</h3><p>Monday Saturday<br>10:00 AM 7:00 PM<br>Sunday By Appointment</p></div>
          </div>
        </div>
      </div>
      <div class="image-frame contact-image"><img src="assets/images/contact-photo.jpg" alt="Atelier interior photo"></div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-left">
      <p class="footer-quote">Let's bring your vision to life.</p>
      <div class="footer-brand">
        <img src="assets/images/footer-logo-photo.png" alt="Footer logo photo">
        <div><div class="footer-brand-name">HERMOSO ATELIER</div><div class="footer-brand-tagline">CRAFTING ELEGANCE, DEFINING YOU</div></div>
      </div>
    </div>
    <div class="footer-nav">

  <div>
    <a href="#home">HOME</a>
    <a href="#about">ABOUT US</a>
  </div>

  <div>
    <a href="#services">SERVICES</a>
    <a href="#gallery">GALLERY</a>
  </div>

  <div>
    <a href="#contact">CONTACT US</a>
  </div>

  <p class="copyright">&copy; HermosoAtelier.com</p>

</div>
  <div class="footer-social">

  <a href="#" aria-label="Facebook">
    <span class="social-icon">
      <i class="fa-brands fa-facebook-f"></i>
    </span>
    <span>fb.hermosoatelier.com</span>
  </a>

  <a href="#" aria-label="Instagram">
    <span class="social-icon">
      <i class="fa-brands fa-instagram"></i>
    </span>
    <span>@hermosoatelier</span>
  </a>

  <a href="#" aria-label="Pinterest">
    <span class="social-icon">
      <i class="fa-brands fa-pinterest-p"></i>
    </span>
    <span>hermosoatelier</span>
  </a>

</div>
  <script src="assets/js/script.js"></script>
</body>
</html>
