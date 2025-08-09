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
        <?php if (!empty($region['image'])): ?>
        <div class="region-image">
            <img src="<?= base_url('uploads/regions/' . $region['image']) ?>" 
                 alt="<?= esc($region['name']) ?>" loading="lazy">
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
                <p>No active wineries found in this region yet. Check back soon!</p>
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
