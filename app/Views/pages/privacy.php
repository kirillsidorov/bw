<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>Last updated: <?= date('F j, Y') ?></p>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Information We Collect</h2>
            <p>When you visit Barcelona Wineries, we may collect certain information about your visit, including:</p>
            <ul>
                <li>Information you provide when contacting wineries</li>
                <li>Usage data and analytics</li>
                <li>Device and browser information</li>
                <li>IP address and location data</li>
            </ul>

            <h2>How We Use Information</h2>
            <p>We use the collected information to:</p>
            <ul>
                <li>Improve our website and user experience</li>
                <li>Provide relevant winery recommendations</li>
                <li>Analyze website usage patterns</li>
                <li>Communicate about our services</li>
            </ul>

            <h2>Information Sharing</h2>
            <p>We do not sell, trade, or rent your personal information to third parties. We may share information with:</p>
            <ul>
                <li>Wineries when you contact them through our platform</li>
                <li>Service providers who help operate our website</li>
                <li>Legal authorities when required by law</li>
            </ul>

            <h2>Cookies</h2>
            <p>Our website uses cookies to enhance your browsing experience. You can control cookie settings through your browser preferences.</p>

            <h2>Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

            <h2>Contact Us</h2>
            <p>If you have questions about this Privacy Policy, please contact us at:</p>
            <div class="contact-info">
                <p>Email: privacy@barcelonawineries.com</p>
                <p>Phone: +34 93 123 4567</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>