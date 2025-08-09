<?php
// ============================================================================
// ПОЛНОСТЬЮ ОБНОВЛЕННАЯ СТРАНИЦА ВИНОДЕЛЬНИ - app/Views/wineries/detail.php
// ============================================================================
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* Enhanced styles for new features */
.winery-section {
    background: white;
    margin-bottom: 2rem;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.winery-section h3 {
    color: #722f37;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #28a745;
    transition: all 0.3s;
}

.feature-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.feature-item.unavailable {
    background: #f5f5f5;
    border-left-color: #dc3545;
    opacity: 0.6;
}

.feature-icon {
    color: #722f37;
    margin-right: 1rem;
    font-size: 1.2rem;
    min-width: 20px;
}

.feature-text {
    color: #333;
    font-weight: 500;
    flex: 1;
}

.rating-display {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.rating-score {
    font-size: 2rem;
    font-weight: bold;
    color: #722f37;
    margin-right: 1rem;
}

.rating-stars {
    color: #ffd700;
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.rating-text {
    color: #666;
}

.gallery-container {
    margin-bottom: 3rem;
}

.main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 1rem;
    cursor: pointer;
}

.gallery-thumbnails {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.5rem;
}

.gallery-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.gallery-thumb:hover,
.gallery-thumb.active {
    border-color: #722f37;
    transform: scale(1.05);
}

.gallery-placeholder {
    height: 400px;
    background: linear-gradient(45deg, #722f37, #8b3a42);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 1rem;
}

.highlight-box {
    background: linear-gradient(135deg, #722f37, #8b3a42);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.highlight-title {
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.highlight-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.highlight-feature {
    display: flex;
    align-items: center;
    opacity: 0.9;
}

.highlight-feature i {
    margin-right: 0.8rem;
    font-size: 1.1rem;
}

.two-column {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    align-items: start;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.info-icon {
    color: #722f37;
    margin-right: 1rem;
    font-size: 1.2rem;
    margin-top: 0.2rem;
    min-width: 20px;
}

.info-content {
    flex: 1;
}

.info-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.3rem;
}

.info-value {
    color: #666;
    line-height: 1.4;
}

.badge-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.badge {
    background: #722f37;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.service-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.service-card.available {
    border-color: #722f37;
    background: #fff;
}

.service-card.available .service-icon {
    color: #722f37;
}

.service-card.unavailable {
    opacity: 0.5;
}

.service-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #999;
}

.service-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

@media (max-width: 768px) {
    .two-column {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .feature-grid,
    .service-grid,
    .info-grid,
    .highlight-features {
        grid-template-columns: 1fr;
    }
    
    .gallery-thumbnails {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>

<?php 
function hasValue($value) {
    return !empty($value) && $value !== null && $value !== '';
}

function displayList($jsonString, $type = 'simple') {
    if (!hasValue($jsonString)) return '';
    
    $items = is_string($jsonString) ? json_decode($jsonString, true) : $jsonString;
    if (!$items || !is_array($items)) return '';
    
    if ($type === 'badges') {
        $output = '<div class="badge-list">';
        foreach ($items as $item) {
            $output .= '<span class="badge">' . esc(ucfirst($item)) . '</span>';
        }
        $output .= '</div>';
        return $output;
    }
    
    return esc(implode(', ', $items));
}

function renderStars($rating) {
    $stars = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    
    for ($i = 0; $i < $fullStars; $i++) {
        $stars .= '★';
    }
    if ($halfStar) {
        $stars .= '☆';
    }
    for ($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++) {
        $stars .= '☆';
    }
    
    return $stars;
}
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav style="background: #f8f9fa; padding: 1rem 0; margin-bottom: 2rem; border-radius: 10px;">
            <a href="<?= base_url() ?>" style="color: #722f37; text-decoration: none;">Home</a> > 
            <a href="<?= base_url('penedes') ?>" style="color: #722f37; text-decoration: none;">Penedès</a> > 
            <span style="color: #666;"><?= esc($winery['name']) ?></span>
        </nav>

        <!-- Rating Display -->
        <?php if (hasValue($winery['google_rating']) && $winery['google_rating'] > 0): ?>
        <div class="rating-display">
            <div class="rating-score"><?= number_format($winery['google_rating'], 1) ?></div>
            <div>
                <div class="rating-stars"><?= renderStars($winery['google_rating']) ?></div>
                <div class="rating-text"><?= number_format($winery['google_reviews_count']) ?> reviews on Google</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Image Gallery -->
        <div class="gallery-container">
            <?php if (hasValue($winery['gallery']) && is_array(json_decode($winery['gallery'], true))): ?>
                <?php $images = json_decode($winery['gallery'], true); ?>
                <img src="<?= base_url('uploads/wineries/gallery/' . $images[0]) ?>" 
                     alt="<?= esc($winery['name']) ?>" 
                     class="main-image" 
                     id="mainImage">
                
                <?php if (count($images) > 1): ?>
                <div class="gallery-thumbnails">
                    <?php foreach ($images as $index => $image): ?>
                    <img src="<?= base_url('uploads/wineries/gallery/' . $image) ?>" 
                         alt="<?= esc($winery['name']) ?> - Image <?= $index + 1 ?>"
                         class="gallery-thumb <?= $index === 0 ? 'active' : '' ?>"
                         onclick="changeMainImage('<?= base_url('uploads/wineries/gallery/' . $image) ?>', this)">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            
            <?php elseif (hasValue($winery['featured_image'])): ?>
                <img src="<?= base_url('uploads/wineries/featured/' . $winery['featured_image']) ?>" 
                     alt="<?= esc($winery['name']) ?>" 
                     class="main-image">
            
            <?php else: ?>
                <div class="gallery-placeholder">
                    <div style="text-align: center;">
                        <i class="fas fa-camera" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.7;"></i>
                        <h3><?= esc($winery['name']) ?></h3>
                        <p>Photo gallery coming soon</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Special Features Highlight -->
        <?php 
        $specialFeatures = [];
        if (!empty($winery['features_sea_views'])) $specialFeatures[] = ['icon' => 'fas fa-water', 'text' => 'Sea Views'];
        if (!empty($winery['specialization_cava'])) $specialFeatures[] = ['icon' => 'fas fa-glass-cheers', 'text' => 'Cava Specialist'];
        if (!empty($winery['experience_expert_sommelier'])) $specialFeatures[] = ['icon' => 'fas fa-user-tie', 'text' => 'Expert Sommelier'];
        if (!empty($winery['entertainment_live_music'])) $specialFeatures[] = ['icon' => 'fas fa-music', 'text' => 'Live Music Events'];
        if (!empty($winery['features_historic_building'])) $specialFeatures[] = ['icon' => 'fas fa-landmark', 'text' => 'Historic Building'];
        if (!empty($winery['groups_hen_stag_parties'])) $specialFeatures[] = ['icon' => 'fas fa-party-horn', 'text' => 'Perfect for Celebrations'];
        ?>

        <?php if (!empty($specialFeatures)): ?>
        <div class="highlight-box">
            <div class="highlight-title">✨ What Makes This Winery Special</div>
            <div class="highlight-features">
                <?php foreach ($specialFeatures as $feature): ?>
                <div class="highlight-feature">
                    <i class="<?= $feature['icon'] ?>"></i>
                    <span><?= $feature['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Winery Header -->
        <div class="winery-section">
            <div class="two-column">
                <div>
                    <h1 style="color: #722f37; font-size: 2.5rem; margin-bottom: 1rem;">
                        <?= esc($winery['name']) ?>
                        <?php if ($winery['family_winery']): ?>
                        <span class="badge" style="font-size: 0.6em; margin-left: 1rem;">Family Winery</span>
                        <?php endif; ?>
                        <?php if ($winery['organic_production']): ?>
                        <span class="badge" style="font-size: 0.6em; background: #28a745;">Organic</span>
                        <?php endif; ?>
                    </h1>
                    
                    <?php if (hasValue($winery['short_description'])): ?>
                    <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem; line-height: 1.6;">
                        <?= esc($winery['short_description']) ?>
                    </p>
                    <?php endif; ?>

                    <!-- Quick Facts -->
                    <div class="info-grid">
                        <?php if (hasValue($winery['founded_year'])): ?>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Founded</div>
                                <div class="info-value"><?= esc($winery['founded_year']) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (hasValue($winery['price_category'])): ?>
                        <div class="info-item">
                            <i class="fas fa-tag info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Price Category</div>
                                <div class="info-value"><?= esc(ucfirst(str_replace('-', ' ', $winery['price_category']))) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (hasValue($winery['drive_time_from_barcelona'])): ?>
                        <div class="info-item">
                            <i class="fas fa-car info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Drive from Barcelona</div>
                                <div class="info-value"><?= esc($winery['drive_time_from_barcelona']) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact & Location -->
                <div class="winery-section" style="margin-bottom: 0;">
                    <h3 style="margin-bottom: 1rem;">Contact Information</h3>
                    
                    <?php if (hasValue($winery['address'])): ?>
                    <div class="info-item" style="margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt info-icon"></i>
                        <div class="info-content">
                            <div class="info-value"><?= esc($winery['address']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (hasValue($winery['phone'])): ?>
                    <div class="info-item" style="margin-bottom: 1rem;">
                        <i class="fas fa-phone info-icon"></i>
                        <div class="info-content">
                            <div class="info-value">
                                <a href="tel:<?= esc($winery['phone']) ?>" style="color: #722f37;">
                                    <?= esc($winery['phone']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (hasValue($winery['website'])): ?>
                    <div class="info-item" style="margin-bottom: 1rem;">
                        <i class="fas fa-globe info-icon"></i>
                        <div class="info-content">
                            <div class="info-value">
                                <a href="<?= esc($winery['website']) ?>" target="_blank" style="color: #722f37;">
                                    Visit Website
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Location Features -->
        <?php 
        $locationFeatures = [];
        if (!empty($winery['features_sea_views'])) $locationFeatures[] = ['icon' => 'fas fa-water', 'text' => 'Sea Views'];
        if (!empty($winery['features_mountain_views'])) $locationFeatures[] = ['icon' => 'fas fa-mountain', 'text' => 'Mountain Views'];
        if (!empty($winery['features_vineyard_views'])) $locationFeatures[] = ['icon' => 'fas fa-seedling', 'text' => 'Vineyard Views'];
        if (!empty($winery['features_historic_building'])) $locationFeatures[] = ['icon' => 'fas fa-landmark', 'text' => 'Historic Building'];
        ?>

        <?php if (!empty($locationFeatures)): ?>
        <div class="winery-section">
            <h3>🏞️ Location Features</h3>
            <div class="feature-grid">
                <?php foreach ($locationFeatures as $feature): ?>
                <div class="feature-item">
                    <i class="<?= $feature['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $feature['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Specializations -->
        <?php 
        $specializations = [];
        if (!empty($winery['specialization_cava'])) $specializations[] = ['icon' => 'fas fa-glass-cheers', 'text' => 'Cava Specialist'];
        if (!empty($winery['specialization_organic'])) $specializations[] = ['icon' => 'fas fa-leaf', 'text' => 'Organic Wines'];
        if (!empty($winery['specialization_biodynamic'])) $specializations[] = ['icon' => 'fas fa-moon', 'text' => 'Biodynamic'];
        if (!empty($winery['specialization_natural_wine'])) $specializations[] = ['icon' => 'fas fa-nature', 'text' => 'Natural Wines'];
        ?>

        <?php if (!empty($specializations)): ?>
        <div class="winery-section">
            <h3>🍾 Wine Specializations</h3>
            <div class="feature-grid">
                <?php foreach ($specializations as $spec): ?>
                <div class="feature-item">
                    <i class="<?= $spec['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $spec['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Experience & Services -->
        <div class="winery-section">
            <h3>🎯 Experience & Services</h3>
            <div class="feature-grid">
                <?php if (!empty($winery['experience_guided_tours'])): ?>
                <div class="feature-item">
                    <i class="fas fa-route feature-icon"></i>
                    <span class="feature-text">Guided Tours</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($winery['experience_expert_sommelier'])): ?>
                <div class="feature-item">
                    <i class="fas fa-user-tie feature-icon"></i>
                    <span class="feature-text">Expert Sommelier</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($winery['experience_educational'])): ?>
                <div class="feature-item">
                    <i class="fas fa-graduation-cap feature-icon"></i>
                    <span class="feature-text">Educational Programs</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($winery['experience_harvest_participation'])): ?>
                <div class="feature-item">
                    <i class="fas fa-hand-holding feature-icon"></i>
                    <span class="feature-text">Harvest Participation</span>
                </div>
                <?php endif; ?>

                <?php if ($winery['tastings_available']): ?>
                <div class="feature-item">
                    <i class="fas fa-wine-glass-alt feature-icon"></i>
                    <span class="feature-text">Wine Tastings
                        <?php if (hasValue($winery['tastings_price'])): ?>
                        <small style="display: block; color: #666;"><?= esc($winery['tastings_price']) ?></small>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if ($winery['wine_shop']): ?>
                <div class="feature-item">
                    <i class="fas fa-shopping-bag feature-icon"></i>
                    <span class="feature-text">Wine Shop</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Entertainment & Events -->
        <?php 
        $entertainment = [];
        if (!empty($winery['entertainment_live_music'])) $entertainment[] = ['icon' => 'fas fa-music', 'text' => 'Live Music'];
        if (!empty($winery['entertainment_cultural_events'])) $entertainment[] = ['icon' => 'fas fa-theater-masks', 'text' => 'Cultural Events'];
        if (!empty($winery['entertainment_festivals'])) $entertainment[] = ['icon' => 'fas fa-calendar-star', 'text' => 'Festivals'];
        if (!empty($winery['entertainment_art_exhibitions'])) $entertainment[] = ['icon' => 'fas fa-palette', 'text' => 'Art Exhibitions'];
        ?>

        <?php if (!empty($entertainment)): ?>
        <div class="winery-section">
            <h3>🎭 Entertainment & Events</h3>
            <div class="feature-grid">
                <?php foreach ($entertainment as $event): ?>
                <div class="feature-item">
                    <i class="<?= $event['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $event['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Group Activities -->
        <?php 
        $groupActivities = [];
        if (!empty($winery['groups_hen_stag_parties'])) $groupActivities[] = ['icon' => 'fas fa-party-horn', 'text' => 'Hen & Stag Parties'];
        if (!empty($winery['groups_corporate_events'])) $groupActivities[] = ['icon' => 'fas fa-building', 'text' => 'Corporate Events'];
        if (!empty($winery['groups_private_parties'])) $groupActivities[] = ['icon' => 'fas fa-users', 'text' => 'Private Parties'];
        if (!empty($winery['groups_team_building'])) $groupActivities[] = ['icon' => 'fas fa-handshake', 'text' => 'Team Building'];
        ?>

        <?php if (!empty($groupActivities)): ?>
        <div class="winery-section">
            <h3>👥 Perfect for Groups</h3>
            <div class="feature-grid">
                <?php foreach ($groupActivities as $activity): ?>
                <div class="feature-item">
                    <i class="<?= $activity['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $activity['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Amenities -->
        <?php 
        $amenities = [];
        if (!empty($winery['amenities_wine_bar'])) $amenities[] = ['icon' => 'fas fa-cocktail', 'text' => 'Wine Bar'];
        if (!empty($winery['amenities_terrace'])) $amenities[] = ['icon' => 'fas fa-umbrella-beach', 'text' => 'Terrace'];
        if (!empty($winery['amenities_garden'])) $amenities[] = ['icon' => 'fas fa-seedling', 'text' => 'Garden'];
        if (!empty($winery['amenities_picnic_area'])) $amenities[] = ['icon' => 'fas fa-tree', 'text' => 'Picnic Area'];
        if (!empty($winery['amenities_playground'])) $amenities[] = ['icon' => 'fas fa-child', 'text' => 'Playground'];
        if ($winery['restaurant_available']) $amenities[] = ['icon' => 'fas fa-utensils', 'text' => 'Restaurant'];
        ?>

        <?php if (!empty($amenities)): ?>
        <div class="winery-section">
            <h3>🏛️ Amenities</h3>
            <div class="feature-grid">
                <?php foreach ($amenities as $amenity): ?>
                <div class="feature-item">
                    <i class="<?= $amenity['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $amenity['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Accessibility -->
        <div class="winery-section">
            <h3>♿ Accessibility</h3>
            <div class="feature-grid">
                <div class="feature-item <?= !empty($winery['accessibility_wheelchair_parking']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-parking feature-icon"></i>
                    <span class="feature-text">Wheelchair-accessible parking</span>
                </div>
                <div class="feature-item <?= !empty($winery['accessibility_wheelchair_entrance']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-wheelchair feature-icon"></i>
                    <span class="feature-text">Wheelchair-accessible entrance</span>
                </div>
                <div class="feature-item <?= !empty($winery['accessibility_wheelchair_restroom']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-restroom feature-icon"></i>
                    <span class="feature-text">Wheelchair-accessible restroom</span>
                </div>
                <div class="feature-item <?= $winery['disabled_access'] ? '' : 'unavailable' ?>">
                    <i class="fas fa-universal-access feature-icon"></i>
                    <span class="feature-text">General disabled access</span>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="winery-section">
            <h3>💳 Payments</h3>
            <div class="feature-grid">
                <div class="feature-item <?= !empty($winery['payment_credit_cards']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-credit-card feature-icon"></i>
                    <span class="feature-text">Credit cards</span>
                </div>
                <div class="feature-item <?= !empty($winery['payment_debit_cards']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-credit-card feature-icon"></i>
                    <span class="feature-text">Debit cards</span>
                </div>
                <div class="feature-item <?= !empty($winery['payment_nfc_mobile']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-mobile-alt feature-icon"></i>
                    <span class="feature-text">NFC mobile payments</span>
                </div>
                <div class="feature-item <?= !empty($winery['payment_cash']) ? '' : 'unavailable' ?>">
                    <i class="fas fa-money-bill feature-icon"></i>
                    <span class="feature-text">Cash</span>
                </div>
            </div>
        </div>

        <!-- About the Winery -->
        <?php if (hasValue($winery['description'])): ?>
        <div class="winery-section">
            <h3>📖 About the Winery</h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #666;">
                <?= nl2br(esc($winery['description'])) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Wine Types & Grape Varieties -->
        <?php if (hasValue($winery['wine_types']) || hasValue($winery['grape_varieties'])): ?>
        <div class="winery-section">
            <h3>🍇 Wines & Grapes</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <?php if (hasValue($winery['wine_types'])): ?>
                <div>
                    <div class="info-label" style="margin-bottom: 1rem;">Wine Types</div>
                    <?= displayList($winery['wine_types'], 'badges') ?>
                </div>
                <?php endif; ?>
                
                <?php if (hasValue($winery['grape_varieties'])): ?>
                <div>
                    <div class="info-label" style="margin-bottom: 1rem;">Grape Varieties</div>
                    <?= displayList($winery['grape_varieties'], 'badges') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Transportation -->
        <div class="winery-section">
            <h3>🚗 How to Get There</h3>
            <div class="info-grid">
                <?php if (hasValue($winery['drive_time_from_barcelona'])): ?>
                <div class="info-item">
                    <i class="fas fa-car info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">By Car from Barcelona</div>
                        <div class="info-value"><?= esc($winery['drive_time_from_barcelona']) ?></div>
                        <?php if ($winery['parking_available']): ?>
                        <div class="info-value" style="margin-top: 0.3rem;">
                            Parking: <?= $winery['parking_type'] === 'free' ? 'Free' : 'Paid' ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasValue($winery['nearest_train_station'])): ?>
                <div class="info-item">
                    <i class="fas fa-train info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">By Train</div>
                        <div class="info-value">Nearest station: <?= esc($winery['nearest_train_station']) ?></div>
                        <?php if (hasValue($winery['train_time_from_barcelona'])): ?>
                        <div class="info-value">Travel time: <?= esc($winery['train_time_from_barcelona']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasValue($winery['taxi_cost_from_barcelona'])): ?>
                <div class="info-item">
                    <i class="fas fa-taxi info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">By Taxi</div>
                        <div class="info-value">Approx. cost: <?= esc($winery['taxi_cost_from_barcelona']) ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Opening Hours -->
        <?php if (hasValue($winery['working_hours_weekdays']) || hasValue($winery['working_hours_weekends'])): ?>
        <div class="winery-section">
            <h3>🕒 Opening Hours</h3>
            <div class="info-grid">
                <?php if (hasValue($winery['working_hours_weekdays'])): ?>
                <div class="info-item">
                    <i class="fas fa-clock info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">Weekdays</div>
                        <div class="info-value"><?= esc($winery['working_hours_weekdays']) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasValue($winery['working_hours_weekends'])): ?>
                <div class="info-item">
                    <i class="fas fa-clock info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">Weekends</div>
                        <div class="info-value"><?= esc($winery['working_hours_weekends']) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasValue($winery['booking_required'])): ?>
                <div class="info-item">
                    <i class="fas fa-calendar-check info-icon"></i>
                    <div class="info-content">
                        <div class="info-label">Booking</div>
                        <div class="info-value">
                            <?php 
                            switch($winery['booking_required']) {
                                case 'required': echo 'Booking Required'; break;
                                case 'recommended': echo 'Booking Recommended'; break;
                                case 'not_required': echo 'Walk-ins Welcome'; break;
                                default: echo 'Booking Recommended';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Service Quality -->
        <?php 
        $serviceFeatures = [];
        if (!empty($winery['service_multilingual_guides'])) $serviceFeatures[] = ['icon' => 'fas fa-language', 'text' => 'Multilingual Guides'];
        if (!empty($winery['service_personalized_experience'])) $serviceFeatures[] = ['icon' => 'fas fa-user-check', 'text' => 'Personalized Experience'];
        if (!empty($winery['service_flexible_schedule'])) $serviceFeatures[] = ['icon' => 'fas fa-clock', 'text' => 'Flexible Schedule'];
        ?>

        <?php if (!empty($serviceFeatures) || hasValue($winery['languages'])): ?>
        <div class="winery-section">
            <h3>⭐ Service Quality</h3>
            <div class="feature-grid">
                <?php foreach ($serviceFeatures as $service): ?>
                <div class="feature-item">
                    <i class="<?= $service['icon'] ?> feature-icon"></i>
                    <span class="feature-text"><?= $service['text'] ?></span>
                </div>
                <?php endforeach; ?>

                <?php if (hasValue($winery['languages'])): ?>
                <div class="feature-item">
                    <i class="fas fa-globe feature-icon"></i>
                    <span class="feature-text">Languages: <?= displayList($winery['languages']) ?></span>
                </div>
                <?php endif; ?>

                <?php if ($winery['pets_allowed']): ?>
                <div class="feature-item">
                    <i class="fas fa-dog feature-icon"></i>
                    <span class="feature-text">Pet Friendly</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Awards & Recognition -->
        <?php if (hasValue($winery['awards_certificates'])): ?>
        <div class="winery-section">
            <h3>🏆 Awards & Recognition</h3>
            <p style="color: #666; line-height: 1.6;"><?= esc($winery['awards_certificates']) ?></p>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
function changeMainImage(src, thumb) {
    document.getElementById('mainImage').src = src;
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    
    // Add active class to clicked thumbnail
    thumb.classList.add('active');
}

// Add smooth scroll to anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Animate feature items on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Animate sections on scroll
    document.querySelectorAll('.winery-section').forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
    });
});
</script>

<?= $this->endSection() ?>