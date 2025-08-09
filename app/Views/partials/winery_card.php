<div class="winery-card">
    <div class="winery-image">
        <?php if (!empty($winery['featured_image'])): ?>
            <img src="<?= base_url('uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image']) ?>" 
                 alt="<?= esc($winery['name']) ?>" loading="lazy">
        <?php else: ?>
            <div class="winery-placeholder">
                <i class="fas fa-wine-glass-alt"></i>
            </div>
        <?php endif; ?>
        
        <?php if ($winery['featured']): ?>
        <span class="featured-badge">Featured</span>
        <?php endif; ?>
    </div>
    
    <div class="winery-info">
        <h3 class="winery-name">
            <a href="<?= base_url('winery/' . $winery['slug']) ?>">
                <?= esc($winery['name']) ?>
            </a>
        </h3>
        
        <div class="winery-location">
            <i class="fas fa-map-marker-alt"></i>
            <span><?= esc($winery['region_name'] ?? 'Unknown Region') ?></span>
        </div>
        
        <?php if (!empty($winery['short_description'])): ?>
        <p class="winery-description">
            <?= esc(mb_substr($winery['short_description'], 0, 120)) ?>
            <?= mb_strlen($winery['short_description']) > 120 ? '...' : '' ?>
        </p>
        <?php endif; ?>
        
        <div class="winery-features">
            <?php if (!empty($winery['wine_types'])): ?>
                <?php foreach (array_slice($winery['wine_types'], 0, 3) as $type): ?>
                <span class="feature-tag"><?= ucfirst($type) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if ($winery['organic_production']): ?>
            <span class="feature-tag organic">Organic</span>
            <?php endif; ?>
        </div>
        
        <div class="winery-services">
            <?php if ($winery['tours_available']): ?>
            <span class="service-icon" title="Tours Available">
                <i class="fas fa-route"></i>
            </span>
            <?php endif; ?>
            
            <?php if ($winery['tastings_available']): ?>
            <span class="service-icon" title="Tastings Available">
                <i class="fas fa-wine-glass-alt"></i>
            </span>
            <?php endif; ?>
            
            <?php if ($winery['restaurant_available']): ?>
            <span class="service-icon" title="Restaurant">
                <i class="fas fa-utensils"></i>
            </span>
            <?php endif; ?>
            
            <?php if ($winery['wine_shop']): ?>
            <span class="service-icon" title="Wine Shop">
                <i class="fas fa-shopping-bag"></i>
            </span>
            <?php endif; ?>
        </div>
        
        <div class="winery-actions">
            <a href="<?= base_url('winery/' . $winery['slug']) ?>" class="btn btn-primary">
                View Details
            </a>
            <?php if (!empty($winery['website'])): ?>
            <a href="<?= esc($winery['website']) ?>" class="btn btn-secondary" target="_blank" rel="noopener">
                Visit Website
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>