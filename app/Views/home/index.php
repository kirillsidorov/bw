<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Discover Barcelona's Finest Wineries</h1>
        <p>Explore premium wineries, tastings, and wine tours in beautiful Catalonia</p>
        
        <!-- Quick Search -->
        <div class="hero-search">
            <?= form_open(base_url(), 'method="get" class="search-form"') ?>
                <input type="text" name="search" class="search-input" 
                       placeholder="Search wineries..." 
                       value="<?= esc($filters['search'] ?? '') ?>">
                <select name="region" class="search-select">
                    <option value="">All Regions</option>
                    <?php foreach ($regions as $region): ?>
                    <option value="<?= $region['id'] ?>" 
                            <?= ($filters['region_id'] ?? '') == $region['id'] ? 'selected' : '' ?>>
                        <?= esc($region['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            <?= form_close() ?>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="filters-section">
    <div class="container">
        <div class="filters-wrapper">
            <h3>Filter Wineries</h3>
            <?= form_open(base_url(), 'method="get" class="filters-form" id="filtersForm"') ?>
                <input type="hidden" name="search" value="<?= esc($filters['search'] ?? '') ?>">
                
                <div class="filter-group">
                    <label>Wine Type:</label>
                    <div class="checkbox-group">
                        <?php foreach ($wine_types as $type): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="wine_type[]" value="<?= $type ?>"
                                   <?= in_array($type, (array)($filters['wine_type'] ?? [])) ? 'checked' : '' ?>>
                            <?= ucfirst($type) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <label>Price Category:</label>
                    <select name="price_category" class="filter-select">
                        <option value="">All Price Ranges</option>
                        <?php foreach ($price_categories as $key => $label): ?>
                        <option value="<?= $key ?>" 
                                <?= ($filters['price_category'] ?? '') === $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Services:</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="tours" value="1"
                                   <?= !empty($filters['tours']) ? 'checked' : '' ?>>
                            Wine Tours
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="tastings" value="1"
                                   <?= !empty($filters['tastings']) ? 'checked' : '' ?>>
                            Tastings
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="restaurant" value="1"
                                   <?= !empty($filters['restaurant']) ? 'checked' : '' ?>>
                            Restaurant
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="organic" value="1"
                                   <?= !empty($filters['organic']) ? 'checked' : '' ?>>
                            Organic
                        </label>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= base_url() ?>" class="btn btn-secondary">Clear All</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</section>

<!-- Statistics -->
<?php if (isset($stats)): ?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total'] ?>+</span>
                <span class="stat-label">Premium Wineries</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $stats['with_tours'] ?></span>
                <span class="stat-label">Wine Tours Available</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $stats['with_tastings'] ?></span>
                <span class="stat-label">Tasting Experiences</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $stats['organic'] ?></span>
                <span class="stat-label">Organic Wineries</span>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Wineries Grid -->
<section class="wineries-section">
    <div class="container">
        <div class="section-header">
            <h2>
                <?php if (!empty(array_filter($filters))): ?>
                    Search Results (<?= count($wineries) ?> wineries found)
                <?php else: ?>
                    Featured Wineries
                <?php endif; ?>
            </h2>
        </div>

        <?php if (empty($wineries)): ?>
            <div class="no-results">
                <i class="fas fa-wine-glass-alt"></i>
                <h3>No wineries found</h3>
                <p>Try adjusting your search criteria or <a href="<?= base_url() ?>">view all wineries</a>.</p>
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