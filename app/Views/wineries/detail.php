<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <div class="container">
        <a href="<?= base_url() ?>">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="<?= base_url($winery['region_slug']) ?>"><?= esc($winery['region_name']) ?></a>
        <i class="fas fa-chevron-right"></i>
        <span><?= esc($winery['name']) ?></span>
    </div>
</nav>

<!-- Winery Header -->
<section class="winery-header">
    <div class="container">
        <div class="winery-images">
            <?php 
            $featuredPath = '';
            $featuredExists = false;
            
            if (!empty($winery['featured_image'])) {
                $featuredPath = FCPATH . 'uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image'];
                $featuredExists = file_exists($featuredPath);
            }
            ?>
            
            <div class="featured-image">
                <?php if ($featuredExists): ?>
                    <img src="<?= base_url('uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image']) ?>" 
                         alt="<?= esc($winery['name']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="featured-placeholder">
                        <i class="fas fa-wine-glass-alt"></i>
                        <div class="placeholder-text">
                            <?= esc($winery['name']) ?><br>
                            <small>Featured Image</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($winery['gallery']) && is_array($winery['gallery'])): ?>
            <div class="gallery-thumbnails">
                <?php 
                $galleryCount = 0;
                foreach (array_slice($winery['gallery'], 0, 4) as $image): 
                    $galleryPath = FCPATH . 'uploads/wineries/gallery/' . $winery['id'] . '/' . $image;
                    if (file_exists($galleryPath)):
                        $galleryCount++;
                ?>
                <img src="<?= base_url('uploads/wineries/gallery/' . $winery['id'] . '/' . $image) ?>" 
                     alt="<?= esc($winery['name']) ?>" class="gallery-thumb" loading="lazy">
                <?php 
                    endif;
                endforeach; 
                
                // Fill remaining slots with placeholders if we have less than 4 images
                for ($i = $galleryCount; $i < 4; $i++):
                ?>
                <div class="gallery-placeholder">
                    <i class="fas fa-image"></i>
                </div>
                <?php endfor; ?>
            </div>
            <?php else: ?>
            <div class="gallery-thumbnails">
                <div class="gallery-placeholder"><i class="fas fa-image"></i></div>
                <div class="gallery-placeholder"><i class="fas fa-image"></i></div>
                <div class="gallery-placeholder"><i class="fas fa-image"></i></div>
                <div class="gallery-placeholder"><i class="fas fa-image"></i></div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="winery-main-info">
            <div class="winery-title">
                <?php 
                $logoPath = '';
                $logoExists = false;
                
                if (!empty($winery['logo'])) {
                    $logoPath = FCPATH . 'uploads/wineries/logos/' . $winery['id'] . '/' . $winery['logo'];
                    $logoExists = file_exists($logoPath);
                }
                ?>
                
                <?php if ($logoExists): ?>
                    <img src="<?= base_url('uploads/wineries/logos/' . $winery['id'] . '/' . $winery['logo']) ?>" 
                         alt="<?= esc($winery['name']) ?> logo" class="winery-logo">
                <?php else: ?>
                    <div class="logo-placeholder">
                        <?= strtoupper(substr($winery['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <h1><?= esc($winery['name']) ?></h1>
            </div>
            
            <?php if (!empty($winery['short_description'])): ?>
            <p class="winery-summary"><?= esc($winery['short_description']) ?></p>
            <?php endif; ?>
            
            <div class="winery-highlights">
                <?php if ($winery['founded_year']): ?>
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
                <span class="highlight">
                    <i class="fas fa-wine-glass"></i>
                    <?php 
                    $wine_types = is_string($winery['wine_types']) ? json_decode($winery['wine_types'], true) : $winery['wine_types'];
                    if (is_array($wine_types)):
                        echo implode(', ', array_map('ucfirst', $wine_types));
                    endif;
                    ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Add Admin Link for Image Management
            <div class="admin-actions" style="margin-top: 1rem;">
                <a href="<?= base_url('admin/images/' . $winery['slug']) ?>" class="btn btn-secondary">
                    <i class="fas fa-images"></i> Manage Images
                </a>
            </div>
                -->
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

                <!-- Services -->
                <?php 
                $services = [];
                if ($winery['tours_available']) $services[] = 'tours';
                if ($winery['tastings_available']) $services[] = 'tastings';
                if ($winery['restaurant_available']) $services[] = 'restaurant';
                if ($winery['wine_shop']) $services[] = 'shop';
                if (!empty($services)): 
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
                    </div>
                </div>
                <?php endif; ?>

                <!-- Transportation -->
                <?php if (!empty($winery['drive_time_from_barcelona']) || !empty($winery['nearest_train_station'])): ?>
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
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['nearest_train_station'])): ?>
                        <div class="transport-item">
                            <i class="fas fa-train"></i>
                            <h3>By Train</h3>
                            <p>Nearest station: <?= esc($winery['nearest_train_station']) ?></p>
                            <?php if (!empty($winery['train_time_from_barcelona'])): ?>
                            <p>Travel time: <?= esc($winery['train_time_from_barcelona']) ?></p>
                            <?php endif; ?>
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
                            <a href="<?= esc($winery['website']) ?>" target="_blank" rel="noopener nofollow">
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
                            <strong>Weekdays:</strong>
                            <span><?= esc($winery['working_hours_weekdays']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['working_hours_weekends'])): ?>
                        <div class="hours-item">
                            <strong>Weekends:</strong>
                            <span><?= esc($winery['working_hours_weekends']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($winery['booking_required'] !== 'not_required'): ?>
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
                        <?php if (!empty($winery['grape_varieties'])): ?>
                        <div class="fact-item">
                            <strong>Grape Varieties:</strong>
                            <span>
                                <?php 
                                $grape_varieties = is_string($winery['grape_varieties']) ? json_decode($winery['grape_varieties'], true) : $winery['grape_varieties'];
                                if (is_array($grape_varieties)):
                                    echo implode(', ', $grape_varieties);
                                endif;
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['vineyard_size'])): ?>
                        <div class="fact-item">
                            <strong>Vineyard Size:</strong>
                            <span><?= esc($winery['vineyard_size']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($winery['languages'])): ?>
                        <div class="fact-item">
                            <strong>Languages:</strong>
                            <span>
                                <?php 
                                $languages = is_string($winery['languages']) ? json_decode($winery['languages'], true) : $winery['languages'];
                                if (is_array($languages)):
                                    echo implode(', ', $languages);
                                endif;
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <div class="fact-item">
                            <strong>Price Category:</strong>
                            <span><?= ucfirst(str_replace('-', ' ', $winery['price_category'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Wineries -->
<?php if (!empty($recommended_wineries)): ?>
<section class="related-wineries">
    <div class="container">
        <h2>More Wineries in <?= esc($winery['region_name']) ?></h2>
        <div class="wineries-grid">
            <?php foreach ($recommended_wineries as $related): ?>
                <?= view('partials/winery_card', ['winery' => $related]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>