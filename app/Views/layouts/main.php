<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Barcelona Wineries') ?></title>
    <meta name="description" content="<?= esc($meta_description ?? 'Discover the best wineries near Barcelona') ?>">
    <?php if (isset($meta_keywords)): ?>
    <meta name="keywords" content="<?= esc($meta_keywords) ?>">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url() ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= esc($title ?? 'Barcelona Wineries') ?>">
    <meta property="og:description" content="<?= esc($meta_description ?? 'Discover the best wineries near Barcelona') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico') ?>">
    
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/style.css') ?>" rel="stylesheet">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="<?= base_url() ?>" class="logo">
                <i class="fas fa-wine-glass-alt"></i>
                Barcelona Wineries
            </a>
            <ul class="nav-menu">
                <li><a href="<?= base_url() ?>" <?= (uri_string() === '') ? 'class="active"' : '' ?>>Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Regions <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($regions ?? [] as $region): ?>
                        <li><a href="<?= base_url($region['slug']) ?>"><?= esc($region['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <button class="mobile-menu" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Barcelona Wineries</h4>
                <p>Your guide to the finest wine experiences in Catalonia. Discover, taste, and explore the rich wine heritage of the Barcelona region.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="<?= base_url() ?>">Home</a>
                <a href="<?= base_url('about') ?>">About Us</a>
                <a href="<?= base_url('contact') ?>">Contact</a>
                <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>
            </div>
            <div class="footer-section">
                <h4>Wine Regions</h4>
                <?php foreach ($regions ?? [] as $region): ?>
                <a href="<?= base_url($region['slug']) ?>"><?= esc($region['name']) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p><i class="fas fa-envelope"></i> info@barcelonawineries.com</p>
                <p><i class="fas fa-phone"></i> +34 93 123 4567</p>
                <p><i class="fas fa-map-marker-alt"></i> Barcelona, Catalonia</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Barcelona Wineries. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?= base_url('public/assets/js/main.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>