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
        
        <?php if (!empty($winery['featured']) && $winery['featured']): ?>
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
            <?php if (!empty($winery['drive_time_from_barcelona'])): ?>
            <span class="drive-time"> • <?= esc($winery['drive_time_from_barcelona']) ?> from Barcelona</span>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($winery['short_description'])): ?>
        <p class="winery-description">
            <?= esc(mb_substr($winery['short_description'], 0, 120)) ?>
            <?= mb_strlen($winery['short_description']) > 120 ? '...' : '' ?>
        </p>
        <?php endif; ?>
        
        <div class="winery-features">
            <?php 
            $wineTypes = [];
            if (!empty($winery['wine_types'])) {
                $wineTypes = is_string($winery['wine_types']) ? json_decode($winery['wine_types'], true) : $winery['wine_types'];
            }
            if (!empty($wineTypes)): 
            ?>
                <?php foreach (array_slice($wineTypes, 0, 3) as $type): ?>
                <span class="feature-tag"><?= ucfirst($type) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($winery['organic_production']) && $winery['organic_production']): ?>
            <span class="feature-tag organic">Organic</span>
            <?php endif; ?>

            <?php if (!empty($winery['family_winery']) && $winery['family_winery']): ?>
            <span class="feature-tag family">Family Winery</span>
            <?php endif; ?>

            <?php if (!empty($winery['price_category'])): ?>
            <span class="feature-tag price-tag">
                <?php 
                $priceLabels = [
                    'budget' => '€-€€',
                    'mid-range' => '€€-€€€',
                    'premium' => '€€€-€€€€',
                    'luxury' => '€€€€+'
                ];
                echo $priceLabels[$winery['price_category']] ?? ucfirst(str_replace('-', ' ', $winery['price_category']));
                ?>
            </span>
            <?php endif; ?>
        </div>
        
        <div class="winery-services">
            <?php if (!empty($winery['tours_available']) && $winery['tours_available']): ?>
            <span class="service-icon" title="Tours Available">
                <i class="fas fa-route"></i>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($winery['tastings_available']) && $winery['tastings_available']): ?>
            <span class="service-icon" title="Tastings Available">
                <i class="fas fa-wine-glass-alt"></i>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($winery['restaurant_available']) && $winery['restaurant_available']): ?>
            <span class="service-icon" title="Restaurant">
                <i class="fas fa-utensils"></i>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($winery['wine_shop']) && $winery['wine_shop']): ?>
            <span class="service-icon" title="Wine Shop">
                <i class="fas fa-shopping-bag"></i>
            </span>
            <?php endif; ?>

            <?php if (!empty($winery['accommodation_available']) && $winery['accommodation_available']): ?>
            <span class="service-icon" title="Accommodation">
                <i class="fas fa-bed"></i>
            </span>
            <?php endif; ?>

            <?php if (!empty($winery['events_weddings']) && $winery['events_weddings']): ?>
            <span class="service-icon" title="Events & Weddings">
                <i class="fas fa-heart"></i>
            </span>
            <?php endif; ?>
        </div>

        <!-- Additional info row -->
        <div class="winery-meta-info">
            <?php if (!empty($winery['founded_year'])): ?>
            <span class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                Est. <?= $winery['founded_year'] ?>
            </span>
            <?php endif; ?>

            <?php if (!empty($winery['booking_required']) && $winery['booking_required'] !== 'not_required'): ?>
            <span class="meta-item booking-required">
                <i class="fas fa-clock"></i>
                <?= $winery['booking_required'] === 'required' ? 'Booking Required' : 'Booking Recommended' ?>
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