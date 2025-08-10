<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <div class="container">
        <a href="<?= base_url() ?>">Home</a>
        <i class="fas fa-chevron-right"></i>
        <?php if (!empty($winery['region_slug'])): ?>
        <a href="<?= base_url($winery['region_slug']) ?>"><?= esc($winery['region_name']) ?></a>
        <i class="fas fa-chevron-right"></i>
        <?php endif; ?>
        <span><?= esc($winery['name']) ?></span>
    </div>
</nav>

<!-- Winery Header -->
<section class="winery-header">
    <div class="container">
        <div class="winery-images">
            <?php if (!empty($winery['featured_image'])): ?>
            <div class="featured-image">
                <img src="<?= base_url('uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image']) ?>" 
                     alt="<?= esc($winery['name']) ?>" loading="lazy">
            </div>
            <?php else: ?>
            <div class="featured-image">
                <div class="winery-placeholder">
                    <i class="fas fa-wine-glass-alt"></i>
                </div>
            </div>
            <?php endif; ?>
            
            <?php 
            $gallery = !empty($winery['gallery']) ? json_decode($winery['gallery'], true) : [];
            if (!empty($gallery)): 
            ?>
            <div class="gallery-thumbnails">
                <?php foreach (array_slice($gallery, 0, 4) as $image): ?>
                <img src="<?= base_url('uploads/wineries/gallery/' . $winery['id'] . '/' . $image) ?>" 
                     alt="<?= esc($winery['name']) ?>" class="gallery-thumb" loading="lazy">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="winery-main-info">
            <div class="winery-title">
                <?php if (!empty($winery['logo'])): ?>
                <img src="<?= base_url('uploads/wineries/logos/' . $winery['id'] . '/' . $winery['logo']) ?>" 
                     alt="<?= esc($winery['name']) ?> logo" class="winery-logo">
                <?php endif; ?>
                <h1><?= esc($winery['name']) ?></h1>
                
                <!-- Admin Image Management Link -->
                <?php if (ENVIRONMENT !== 'production'): ?>
                <a href="<?= base_url('admin/images/' . $winery['slug']) ?>" class="btn btn-secondary" style="margin-left: 1rem;">
                    <i class="fas fa-images"></i> Manage Images
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($winery['short_description'])): ?>
            <p class="winery-summary"><?= esc($winery['short_description']) ?></p>
            <?php endif; ?>
            
            <div class="winery-highlights">
                <?php if (!empty($winery['founded_year'])): ?>
                <span class="highlight">
                    <i class="fas fa-calendar-alt"></i>
                    Founded <?= $winery['founded_year'] ?>
                </span>
                <?php endif; ?>
                
                <?php if ($winery['family_winery']): ?>
                <span class="highlight">
                    <i class="fas fa-users"></i>
                    Family Winery
                </span>
                <?php endif; ?>
                
                <?php if ($winery['organic_production']): ?>
                <span class="highlight">
                    <i class="fas fa-leaf"></i>
                    Organic Production
                </span>
                <?php endif; ?>
                
                <?php if (!empty($winery['wine_types'])): ?>
                <?php $wineTypes = is_string($winery['wine_types']) ? json_decode($winery['wine_types'], true) : $winery['wine_types']; ?>
                <?php if (!empty($wineTypes)): ?>
                <span class="highlight">
                    <i class="fas fa-wine-glass"></i>
                    <?= implode(', ', array_map('ucfirst', $wineTypes)) ?>
                </span>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($winery['vineyard_size'])): ?>
                <span class="highlight">
                    <i class="fas fa-map"></i>
                    <?= esc($winery['vineyard_size']) ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Price Category & Awards -->
            <div class="winery-meta">
                <?php if (!empty($winery['price_category'])): ?>
                <div class="price-indicator">
                    <span class="price-label">Price Range:</span>
                    <span class="price-badges">
                        <?php 
                        $priceLevel = ['budget' => 1, 'mid-range' => 2, 'premium' => 3];
                        $currentLevel = $priceLevel[$winery['price_category']] ?? 2;
                        for($i = 1; $i <= 3; $i++): 
                        ?>
                        <span class="price-badge <?= $i <= $currentLevel ? 'active' : '' ?>">€</span>
                        <?php endfor; ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if (!empty($winery['awards_certificates'])): ?>
                <div class="awards-info">
                    <i class="fas fa-trophy"></i>
                    <span><?= esc($winery['awards_certificates']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Winery Details -->
<section class="winery-details">
    <div class="container">
        <div class="details-grid">
            <!-- Main Information -->
            <div class="details-main">
                <?php if (!empty($winery['description'])): ?>
                <div class="section">
                    <h2>About the Winery</h2>
                    <div class="content">
                        <?= nl2br(esc($winery['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Wine Varieties & Production -->
                <?php if (!empty($winery['grape_varieties']) || !empty($winery['wine_types'])): ?>
                <div class="section">
                    <h2>Wines & Production</h2>
                    <div class="wine-info-grid">
                        <?php if (!empty($winery['grape_varieties'])): ?>
                        <?php $grapeVarieties = is_string($winery['grape_varieties']) ? json_decode($winery['grape_varieties'], true) : $winery['grape_varieties']; ?>
                        <?php if (!empty($grapeVarieties)): ?>
                        <div class="wine-info-item">
                            <h3><i class="fas fa-leaf"></i> Grape Varieties</h3>
                            <p><?= implode(', ', $grapeVarieties) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!empty($winery['wine_types'])): ?>
                        <?php $wineTypes = is_string($winery['wine_types']) ? json_decode($winery['wine_types'], true) : $winery['wine_types']; ?>
                        <?php if (!empty($wineTypes)): ?>
                        <div class="wine-info-item">
                            <h3><i class="fas fa-wine-glass"></i> Wine Types</h3>
                            <p><?= implode(', ', array_map('ucfirst', $wineTypes)) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!empty($winery['vineyard_size'])): ?>
                        <div class="wine-info-item">
                            <h3><i class="fas fa-map"></i> Vineyard Size</h3>
                            <p><?= esc($winery['vineyard_size']) ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['organic_production']): ?>
                        <div class="wine-info-item">
                            <h3><i class="fas fa-leaf"></i> Production Method</h3>
                            <p>Certified Organic Production</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Services & Experiences -->
                <?php 
                $hasServices = $winery['tours_available'] || $winery['tastings_available'] || 
                              $winery['restaurant_available'] || $winery['wine_shop'] ||
                              $winery['accommodation_available'] || $winery['events_weddings'];
                if ($hasServices): 
                ?>
                <div class="section">
                    <h2>Services & Experiences</h2>
                    <div class="services-grid">
                        <?php if ($winery['tours_available']): ?>
                        <div class="service-item">
                            <i class="fas fa-route"></i>
                            <h3>Wine Tours</h3>
                            <?php if (!empty($winery['tours_description'])): ?>
                            <p><?= esc($winery['tours_description']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($winery['tours_price'])): ?>
                            <span class="price"><?= esc($winery['tours_price']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['tastings_available']): ?>
                        <div class="service-item">
                            <i class="fas fa-wine-glass-alt"></i>
                            <h3>Wine Tastings</h3>
                            <?php if (!empty($winery['tastings_duration'])): ?>
                            <p>Duration: <?= esc($winery['tastings_duration']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($winery['tastings_price'])): ?>
                            <span class="price"><?= esc($winery['tastings_price']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['restaurant_available']): ?>
                        <div class="service-item">
                            <i class="fas fa-utensils"></i>
                            <h3>Restaurant</h3>
                            <?php if (!empty($winery['restaurant_description'])): ?>
                            <p><?= esc($winery['restaurant_description']) ?></p>
                            <?php else: ?>
                            <p>Fine dining with wine pairings</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['wine_shop']): ?>
                        <div class="service-item">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>Wine Shop</h3>
                            <p>Purchase wines directly from the winery</p>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['accommodation_available']): ?>
                        <div class="service-item">
                            <i class="fas fa-bed"></i>
                            <h3>Accommodation</h3>
                            <p>Stay overnight at the winery</p>
                            <?php if (!empty($winery['accommodation_link'])): ?>
                            <a href="<?= esc($winery['accommodation_link']) ?>" target="_blank" class="btn btn-secondary btn-sm">Book Stay</a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['events_weddings']): ?>
                        <div class="service-item">
                            <i class="fas fa-heart"></i>
                            <h3>Events & Weddings</h3>
                            <?php if (!empty($winery['events_description'])): ?>
                            <p><?= esc($winery['events_description']) ?></p>
                            <?php else: ?>
                            <p>Private events and wedding ceremonies</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['kids_programs']): ?>
                        <div class="service-item">
                            <i class="fas fa-child"></i>
                            <h3>Family Programs</h3>
                            <p>Kid-friendly activities and programs</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Accessibility & Facilities -->
                <?php if ($winery['disabled_access'] || $winery['parking_available'] || !empty($winery['languages']) || $winery['pets_allowed']): ?>
                <div class="section">
                    <h2>Facilities & Accessibility</h2>
                    
                    <!-- General Accessibility -->
                    <div class="facilities-grid">
                        <?php if ($winery['disabled_access']): ?>
                        <div class="facility-item">
                            <i class="fas fa-wheelchair"></i>
                            <span>Wheelchair Accessible</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['parking_available']): ?>
                        <div class="facility-item">
                            <i class="fas fa-parking"></i>
                            <span><?= ucfirst($winery['parking_type'] ?? 'Available') ?> Parking</span>
                            <?php if (!empty($winery['parking_spaces'])): ?>
                            <span>(<?= esc($winery['parking_spaces']) ?> spaces)</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['languages'])): ?>
                        <?php $languages = is_string($winery['languages']) ? json_decode($winery['languages'], true) : $winery['languages']; ?>
                        <?php if (!empty($languages)): ?>
                        <div class="facility-item">
                            <i class="fas fa-language"></i>
                            <span><?= implode(', ', $languages) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($winery['pets_allowed']): ?>
                        <div class="facility-item">
                            <i class="fas fa-paw"></i>
                            <span>Pet Friendly</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Detailed Accessibility -->
                    <?php if ($winery['accessibility_wheelchair_parking'] || $winery['accessibility_wheelchair_entrance'] || 
                             $winery['accessibility_wheelchair_restroom'] || $winery['accessibility_wheelchair_seating']): ?>
                    <h3 style="margin-top: 2rem;">Wheelchair Accessibility</h3>
                    <div class="accessibility-details">
                        <?php if ($winery['accessibility_wheelchair_parking']): ?>
                        <div class="accessibility-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Wheelchair accessible parking</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['accessibility_wheelchair_entrance']): ?>
                        <div class="accessibility-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Wheelchair accessible entrance</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['accessibility_wheelchair_restroom']): ?>
                        <div class="accessibility-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Wheelchair accessible restroom</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['accessibility_wheelchair_seating']): ?>
                        <div class="accessibility-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Wheelchair accessible seating</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Payment Methods -->
                    <?php if ($winery['payment_credit_cards'] || $winery['payment_debit_cards'] || 
                             $winery['payment_nfc_mobile'] || $winery['payment_cash']): ?>
                    <h3 style="margin-top: 2rem;">Payment Methods</h3>
                    <div class="payment-methods">
                        <?php if ($winery['payment_credit_cards']): ?>
                        <div class="payment-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Credit Cards</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['payment_debit_cards']): ?>
                        <div class="payment-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Debit Cards</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['payment_nfc_mobile']): ?>
                        <div class="payment-item">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Contactless/Mobile Pay</span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['payment_cash']): ?>
                        <div class="payment-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Cash</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Amenities -->
                    <?php 
                    $amenities = [
                        'amenities_outdoor_seating' => ['icon' => 'chair', 'label' => 'Outdoor Seating'],
                        'amenities_dog_friendly' => ['icon' => 'dog', 'label' => 'Dog Friendly'],
                        'amenities_family_friendly' => ['icon' => 'users', 'label' => 'Family Friendly'],
                        'amenities_groups' => ['icon' => 'user-friends', 'label' => 'Group Friendly'],
                        'amenities_wine_bar' => ['icon' => 'wine-glass', 'label' => 'Wine Bar'],
                        'amenities_terrace' => ['icon' => 'mountain', 'label' => 'Terrace'],
                        'amenities_garden' => ['icon' => 'seedling', 'label' => 'Garden'],
                        'amenities_picnic_area' => ['icon' => 'tree', 'label' => 'Picnic Area'],
                        'amenities_playground' => ['icon' => 'playground', 'label' => 'Playground']
                    ];
                    
                    $hasAmenities = false;
                    foreach ($amenities as $field => $info) {
                        if (!empty($winery[$field])) {
                            $hasAmenities = true;
                            break;
                        }
                    }
                    ?>

                    <?php if ($hasAmenities): ?>
                    <h3 style="margin-top: 2rem;">Amenities</h3>
                    <div class="amenities-grid">
                        <?php foreach ($amenities as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="amenity-item">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <span><?= $info['label'] ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Transportation -->
                <?php if (!empty($winery['drive_time_from_barcelona']) || !empty($winery['nearest_train_station']) || !empty($winery['bus_routes'])): ?>
                <div class="section">
                    <h2>How to Get There</h2>
                    <div class="transport-options">
                        <?php if (!empty($winery['drive_time_from_barcelona'])): ?>
                        <div class="transport-item">
                            <i class="fas fa-car"></i>
                            <h3>By Car</h3>
                            <p>Drive time from Barcelona: <?= esc($winery['drive_time_from_barcelona']) ?></p>
                            <?php if ($winery['parking_available']): ?>
                            <p>Parking: <?= $winery['parking_type'] === 'free' ? 'Free' : 'Paid' ?> parking available</p>
                            <?php endif; ?>
                            <?php if (!empty($winery['driving_instructions'])): ?>
                            <p><small><?= esc($winery['driving_instructions']) ?></small></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['nearest_train_station'])): ?>
                        <div class="transport-item">
                            <i class="fas fa-train"></i>
                            <h3>By Train</h3>
                            <p>Nearest station: <?= esc($winery['nearest_train_station']) ?></p>
                            <?php if (!empty($winery['train_line'])): ?>
                            <p>Line: <?= esc($winery['train_line']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($winery['train_time_from_barcelona'])): ?>
                            <p>Travel time: <?= esc($winery['train_time_from_barcelona']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($winery['distance_from_station'])): ?>
                            <p>Distance from station: <?= esc($winery['distance_from_station']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['bus_routes'])): ?>
                        <div class="transport-item">
                            <i class="fas fa-bus"></i>
                            <h3>Public Transport</h3>
                            <p><?= esc($winery['bus_routes']) ?></p>
                            <?php if (!empty($winery['public_transport_cost'])): ?>
                            <p>Cost: <?= esc($winery['public_transport_cost']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['taxi_cost_from_barcelona'])): ?>
                        <div class="transport-item">
                            <i class="fas fa-taxi"></i>
                            <h3>By Taxi</h3>
                            <p>Approximate cost from Barcelona: <?= esc($winery['taxi_cost_from_barcelona']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Features & Views -->
                <?php 
                $features = [
                    'features_sea_views' => ['icon' => 'water', 'label' => 'Sea Views'],
                    'features_mountain_views' => ['icon' => 'mountain', 'label' => 'Mountain Views'],
                    'features_vineyard_views' => ['icon' => 'leaf', 'label' => 'Vineyard Views'],
                    'features_historic_building' => ['icon' => 'landmark', 'label' => 'Historic Building']
                ];
                
                $hasFeatures = false;
                foreach ($features as $field => $info) {
                    if (!empty($winery[$field])) {
                        $hasFeatures = true;
                        break;
                    }
                }
                ?>

                <?php if ($hasFeatures): ?>
                <div class="section">
                    <h2>Special Features & Views</h2>
                    <div class="features-grid">
                        <?php foreach ($features as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="feature-highlight">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <span><?= $info['label'] ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Wine Specialization -->
                <?php 
                $specializations = [
                    'specialization_cava' => ['icon' => 'wine-bottle', 'label' => 'Cava Specialist'],
                    'specialization_organic' => ['icon' => 'leaf', 'label' => 'Organic Wines'],
                    'specialization_biodynamic' => ['icon' => 'seedling', 'label' => 'Biodynamic Wines'],
                    'specialization_natural_wine' => ['icon' => 'nature', 'label' => 'Natural Wines']
                ];
                
                $hasSpecializations = false;
                foreach ($specializations as $field => $info) {
                    if (!empty($winery[$field])) {
                        $hasSpecializations = true;
                        break;
                    }
                }
                ?>

                <?php if ($hasSpecializations): ?>
                <div class="section">
                    <h2>Wine Specializations</h2>
                    <div class="specializations-grid">
                        <?php foreach ($specializations as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="specialization-item">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <h4><?= $info['label'] ?></h4>
                                <p>Expert in <?= strtolower($info['label']) ?> production and tasting.</p>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Enhanced Experiences -->
                <?php 
                $experiences = [
                    'experience_guided_tours' => ['icon' => 'route', 'label' => 'Expert Guided Tours'],
                    'experience_expert_sommelier' => ['icon' => 'user-graduate', 'label' => 'Expert Sommelier'],
                    'experience_educational' => ['icon' => 'chalkboard-teacher', 'label' => 'Educational Programs'],
                    'experience_harvest_participation' => ['icon' => 'tractor', 'label' => 'Harvest Experience']
                ];
                
                $hasExperiences = false;
                foreach ($experiences as $field => $info) {
                    if (!empty($winery[$field])) {
                        $hasExperiences = true;
                        break;
                    }
                }
                ?>

                <?php if ($hasExperiences): ?>
                <div class="section">
                    <h2>Premium Experiences</h2>
                    <div class="experiences-grid">
                        <?php foreach ($experiences as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="experience-item">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <h4><?= $info['label'] ?></h4>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Entertainment & Events -->
                <?php 
                $entertainment = [
                    'entertainment_live_music' => ['icon' => 'music', 'label' => 'Live Music'],
                    'entertainment_cultural_events' => ['icon' => 'theater-masks', 'label' => 'Cultural Events'],
                    'entertainment_festivals' => ['icon' => 'calendar-star', 'label' => 'Wine Festivals'],
                    'entertainment_art_exhibitions' => ['icon' => 'palette', 'label' => 'Art Exhibitions']
                ];
                
                $hasEntertainment = false;
                foreach ($entertainment as $field => $info) {
                    if (!empty($winery[$field])) {
                        $hasEntertainment = true;
                        break;
                    }
                }
                ?>

                <?php if ($hasEntertainment): ?>
                <div class="section">
                    <h2>Entertainment & Cultural Events</h2>
                    <div class="entertainment-grid">
                        <?php foreach ($entertainment as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="entertainment-item">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <span><?= $info['label'] ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Group Services -->
                <?php 
                $groupServices = [
                    'groups_hen_stag_parties' => ['icon' => 'glass-cheers', 'label' => 'Bachelor/Bachelorette Parties'],
                    'groups_corporate_events' => ['icon' => 'briefcase', 'label' => 'Corporate Events'],
                    'groups_private_parties' => ['icon' => 'birthday-cake', 'label' => 'Private Parties'],
                    'groups_team_building' => ['icon' => 'handshake', 'label' => 'Team Building']
                ];
                
                $hasGroupServices = false;
                foreach ($groupServices as $field => $info) {
                    if (!empty($winery[$field])) {
                        $hasGroupServices = true;
                        break;
                    }
                }
                ?>

                <?php if ($hasGroupServices): ?>
                <div class="section">
                    <h2>Group Events & Celebrations</h2>
                    <div class="group-services-grid">
                        <?php foreach ($groupServices as $field => $info): ?>
                            <?php if (!empty($winery[$field])): ?>
                            <div class="group-service-item">
                                <i class="fas fa-<?= $info['icon'] ?>"></i>
                                <h4><?= $info['label'] ?></h4>
                                <p>Professional event planning and wine experiences for groups.</p>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($winery['seasonal_info']) || !empty($winery['best_visit_time']) || !empty($winery['seasonal_features'])): ?>
                <div class="section">
                    <h2>Seasonal Information</h2>
                    <div class="content">
                        <?php if (!empty($winery['seasonal_info'])): ?>
                        <div class="seasonal-info">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <h4>Seasonal Schedule</h4>
                                <p><?= nl2br(esc($winery['seasonal_info'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['best_visit_time'])): ?>
                        <div class="seasonal-info">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Best Time to Visit</h4>
                                <p><?= nl2br(esc($winery['best_visit_time'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['seasonal_features'])): ?>
                        <div class="seasonal-info">
                            <i class="fas fa-leaf"></i>
                            <div>
                                <h4>Seasonal Features</h4>
                                <p><?= nl2br(esc($winery['seasonal_features'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="details-sidebar">
                <!-- Contact Information -->
                <div class="sidebar-section">
                    <h3>Contact Information</h3>
                    <div class="contact-info">
                        <?php if (!empty($winery['address'])): ?>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= esc($winery['address']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['phone'])): ?>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?= esc($winery['phone']) ?>"><?= esc($winery['phone']) ?></a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['email'])): ?>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?= esc($winery['email']) ?>"><?= esc($winery['email']) ?></a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['website'])): ?>
                        <div class="contact-item">
                            <i class="fas fa-globe"></i>
                            <a href="<?= esc($winery['website']) ?>" target="_blank" rel="noopener">
                                Visit Website
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Opening Hours -->
                <?php if (!empty($winery['working_hours_weekdays']) || !empty($winery['working_hours_weekends'])): ?>
                <div class="sidebar-section">
                    <h3>Opening Hours</h3>
                    <div class="opening-hours">
                        <?php if (!empty($winery['working_hours_weekdays'])): ?>
                        <div class="hours-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Weekdays:</strong>
                                <span><?= esc($winery['working_hours_weekdays']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['working_hours_weekends'])): ?>
                        <div class="hours-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Weekends:</strong>
                                <span><?= esc($winery['working_hours_weekends']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['booking_required']) && $winery['booking_required'] !== 'not_required'): ?>
                        <div class="booking-note">
                            <i class="fas fa-info-circle"></i>
                            <span>
                                <?= $winery['booking_required'] === 'required' ? 'Booking required' : 'Booking recommended' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Facts -->
                <div class="sidebar-section">
                    <h3>Quick Facts</h3>
                    <div class="quick-facts">
                        <?php if (!empty($winery['price_category'])): ?>
                        <div class="fact-item">
                            <i class="fas fa-euro-sign"></i>
                            <div>
                                <strong>Price Category:</strong>
                                <span><?= ucfirst(str_replace('-', ' ', $winery['price_category'])) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['founded_year'])): ?>
                        <div class="fact-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Founded:</strong>
                                <span><?= $winery['founded_year'] ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['family_winery']): ?>
                        <div class="fact-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Type:</strong>
                                <span>Family Winery</span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['organic_production']): ?>
                        <div class="fact-item">
                            <i class="fas fa-leaf"></i>
                            <div>
                                <strong>Production:</strong>
                                <span>Organic</span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['drive_time_from_barcelona'])): ?>
                        <div class="fact-item">
                            <i class="fas fa-car"></i>
                            <div>
                                <strong>From Barcelona:</strong>
                                <span><?= esc($winery['drive_time_from_barcelona']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['views_count'])): ?>
                        <div class="fact-item">
                            <i class="fas fa-eye"></i>
                            <div>
                                <strong>Page Views:</strong>
                                <span><?= number_format($winery['views_count']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ratings & Reviews -->
                <?php if (!empty($winery['google_rating']) || !empty($winery['tripadvisor_rating'])): ?>
                <div class="sidebar-section">
                    <h3>Ratings & Reviews</h3>
                    <div class="ratings-info">
                        <?php if (!empty($winery['google_rating']) && $winery['google_rating'] > 0): ?>
                        <div class="rating-item">
                            <div class="rating-header">
                                <i class="fab fa-google"></i>
                                <span class="rating-source">Google</span>
                            </div>
                            <div class="rating-display">
                                <span class="rating-score"><?= number_format($winery['google_rating'], 1) ?></span>
                                <div class="rating-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $winery['google_rating'] ? 'active' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <?php if (!empty($winery['google_reviews_count'])): ?>
                                <span class="reviews-count">(<?= number_format($winery['google_reviews_count']) ?> reviews)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['tripadvisor_rating']) && $winery['tripadvisor_rating'] > 0): ?>
                        <div class="rating-item">
                            <div class="rating-header">
                                <i class="fab fa-tripadvisor"></i>
                                <span class="rating-source">TripAdvisor</span>
                            </div>
                            <div class="rating-display">
                                <span class="rating-score"><?= number_format($winery['tripadvisor_rating'], 1) ?></span>
                                <div class="rating-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $winery['tripadvisor_rating'] ? 'active' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <?php if (!empty($winery['tripadvisor_reviews_count'])): ?>
                                <span class="reviews-count">(<?= number_format($winery['tripadvisor_reviews_count']) ?> reviews)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($winery['latitude']) && !empty($winery['longitude'])): ?>
                <div class="sidebar-section">
                    <h3>Location</h3>
                    <div class="map-container">
                        <div id="wineryMap" style="height: 200px; border-radius: 8px; overflow: hidden;">
                            <!-- Simple map placeholder - можно интегрировать Google Maps или OpenStreetMap -->
                            <div style="background: #f0f0f0; height: 100%; display: flex; align-items: center; justify-content: center; color: #666;">
                                <div style="text-align: center;">
                                    <i class="fas fa-map-marker-alt" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                                    <br>
                                    <small>Interactive map coming soon</small>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                            <i class="fas fa-external-link-alt"></i>
                            <a href="https://www.google.com/maps?q=<?= $winery['latitude'] ?>,<?= $winery['longitude'] ?>" target="_blank">
                                View on Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Additional Videos/Tours -->
                <?php if (!empty($winery['video_url']) || !empty($winery['virtual_tour_url'])): ?>
                <div class="sidebar-section">
                    <h3>Virtual Experience</h3>
                    <div class="virtual-links">
                        <?php if (!empty($winery['video_url'])): ?>
                        <a href="<?= esc($winery['video_url']) ?>" target="_blank" class="btn btn-secondary btn-block">
                            <i class="fas fa-play"></i> Watch Video
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($winery['virtual_tour_url'])): ?>
                        <a href="<?= esc($winery['virtual_tour_url']) ?>" target="_blank" class="btn btn-secondary btn-block">
                            <i class="fas fa-vr-cardboard"></i> Virtual Tour
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Related Wineries -->
<?php if (!empty($related_wineries)): ?>
<section class="related-wineries">
    <div class="container">
        <h2>More Wineries in <?= esc($winery['region_name'] ?? 'This Region') ?></h2>
        <div class="wineries-grid">
            <?php foreach ($related_wineries as $related): ?>
                <?= view('partials/winery_card', ['winery' => $related]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.price-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.price-badges {
    display: flex;
    gap: 0.2rem;
}

.price-badge {
    color: #ddd;
    font-weight: bold;
}

.price-badge.active {
    color: #722f37;
}

.awards-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    color: #722f37;
    font-weight: 500;
}

.wine-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.wine-info-item {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #722f37;
}

.wine-info-item h3 {
    color: #722f37;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem;
    background: white;
    border-radius: 8px;
}

.facility-item i {
    color: #722f37;
    width: 20px;
}

.seasonal-info {
    background: #e8f4fd;
    padding: 1.5rem;
    border-radius: 10px;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.seasonal-info i {
    color: #0066cc;
    font-size: 1.5rem;
    margin-top: 0.2rem;
}

.seasonal-info h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.hours-item, .fact-item {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    margin-bottom: 1rem;
}

.hours-item i, .fact-item i {
    color: #722f37;
    width: 20px;
    margin-top: 0.2rem;
}

.map-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.btn-block {
    display: block;
    width: 100%;
    margin-bottom: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.virtual-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* New styles for extended fields */
.accessibility-details, .payment-methods, .amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.accessibility-item, .payment-item, .amenity-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem;
    background: white;
    border-radius: 8px;
    color: #28a745;
    font-weight: 500;
}

.accessibility-item i, .payment-item i, .amenity-item i {
    color: #28a745;
    width: 20px;
}

.features-grid, .specializations-grid, .experiences-grid, 
.entertainment-grid, .group-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.feature-highlight, .entertainment-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #722f37, #8b3a42);
    color: white;
    border-radius: 10px;
    font-weight: 600;
}

.feature-highlight i, .entertainment-item i {
    font-size: 1.5rem;
}

.specialization-item, .experience-item, .group-service-item {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, border-color 0.2s;
}

.specialization-item:hover, .experience-item:hover, .group-service-item:hover {
    transform: translateY(-5px);
    border-color: #722f37;
}

.specialization-item i, .experience-item i, .group-service-item i {
    font-size: 2.5rem;
    color: #722f37;
    margin-bottom: 1rem;
}

.specialization-item h4, .experience-item h4, .group-service-item h4 {
    color: #722f37;
    margin-bottom: 0.5rem;
}

.ratings-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.rating-item {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

.rating-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.8rem;
    font-weight: 600;
}

.rating-header i {
    font-size: 1.2rem;
}

.rating-header .fa-google {
    color: #4285f4;
}

.rating-header .fa-tripadvisor {
    color: #00af87;
}

.rating-display {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.rating-score {
    font-size: 1.8rem;
    font-weight: bold;
    color: #722f37;
}

.rating-stars {
    display: flex;
    gap: 0.2rem;
}

.rating-stars .fa-star {
    color: #ddd;
    font-size: 0.9rem;
}

.rating-stars .fa-star.active {
    color: #ffc107;
}

.reviews-count {
    font-size: 0.85rem;
    color: #666;
}
</style>
<?= $this->endSection() ?>