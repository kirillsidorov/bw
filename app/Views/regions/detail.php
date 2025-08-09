<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <div class="container">
        <a href="<?= base_url() ?>">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span><?= esc($region['name']) ?></span>
    </div>
</nav>

<!-- Region Header -->
<section class="region-header">
    <div class="container">
        <?php 
        $regionImageUrl = get_region_image_url($region['image'] ?? '');
        ?>
        
        <?php if ($regionImageUrl): ?>
        <div class="region-image">
            <img src="<?= $regionImageUrl ?>" 
                 alt="<?= esc($region['name']) ?>" loading="lazy">
        </div>
        <?php else: ?>
        <div class="region-image">
            <div class="region-placeholder">
                <i class="fas fa-map-marked-alt"></i>
                <div class="placeholder-text">
                    <?= esc($region['name']) ?><br>
                    <small>Wine Region</small>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="region-info">
            <h1><?= esc($region['name']) ?> Wineries</h1>
            
            <?php if (!empty($region['short_description'])): ?>
            <p class="region-summary"><?= esc($region['short_description']) ?></p>
            <?php endif; ?>
            
            <div class="region-stats">
                <span class="stat">
                    <i class="fas fa-wine-bottle"></i>
                    <?= $total_wineries ?> Wineries
                </span>
                <span class="stat">
                    <i class="fas fa-star"></i>
                    <?= count($featured_wineries) ?> Featured
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Region Description -->
<?php if (!empty($region['description'])): ?>
<section class="region-description">
    <div class="container">
        <div class="description-content">
            <?= nl2br(esc($region['description'])) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Region Wineries -->
<section class="region-wineries">
    <div class="container">
        <h2>Wineries in <?= esc($region['name']) ?></h2>
        
        <?php if (empty($wineries)): ?>
            <div class="no-wineries">
                <div class="no-wineries-placeholder">
                    <i class="fas fa-wine-glass-alt"></i>
                    <h3>No wineries found</h3>
                    <p>No active wineries found in this region yet. Check back soon!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="wineries-grid">
                <?php foreach ($wineries as $winery): ?>
                    <?= view('partials/winery_card', ['winery' => $winery]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.region-placeholder {
    background: linear-gradient(135deg, #722f37 0%, #8b3a42 100%);
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
}

.region-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="30" r="1" fill="rgba(255,255,255,0.08)"/><circle cx="40" cy="70" r="2" fill="rgba(255,255,255,0.06)"/><circle cx="80" cy="80" r="1.2" fill="rgba(255,255,255,0.09)"/></svg>');
    opacity: 0.3;
}

.region-placeholder i {
    font-size: 4rem;
    margin-bottom: 1rem;
    z-index: 1;
}

.region-placeholder .placeholder-text {
    font-size: 1.2rem;
    z-index: 1;
    text-align: center;
}

.no-wineries {
    text-align: center;
    padding: 4rem 2rem;
}

.no-wineries-placeholder {
    max-width: 400px;
    margin: 0 auto;
}

.no-wineries-placeholder i {
    font-size: 4rem;
    color: #722f37;
    margin-bottom: 1rem;
}

.no-wineries-placeholder h3 {
    color: #722f37;
    margin-bottom: 1rem;
    font-size: 2rem;
}

.no-wineries-placeholder p {
    color: #666;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .region-placeholder {
        height: 250px;
    }
    
    .region-placeholder i {
        font-size: 3rem;
    }
}
</style>
<?= $this->endSection() ?>