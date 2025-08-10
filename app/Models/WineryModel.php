<?php

namespace App\Models;

use CodeIgniter\Model;

class WineryModel extends Model
{
    protected $table = 'wineries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'slug', 'short_description', 'description', 'address', 
        'latitude', 'longitude', 'phone', 'email', 'website', 'region_id',
        'working_hours_weekdays', 'working_hours_weekends', 'seasonal_info',
        'tours_available', 'tours_description', 'tours_price',
        'tastings_available', 'tastings_price', 'tastings_duration',
        'wine_shop', 'restaurant_available', 'restaurant_description',
        'accommodation_available', 'accommodation_link', 'events_weddings', 
        'events_description', 'kids_programs', 'disabled_access',
        'founded_year', 'vineyard_size', 'grape_varieties', 'wine_types',
        'price_category', 'organic_production', 'family_winery', 'awards_certificates',
        'nearest_train_station', 'train_line', 'train_time_from_barcelona',
        'bus_routes', 'distance_from_station', 'public_transport_cost',
        'transport_schedule', 'walking_accessibility', 'drive_time_from_barcelona',
        'parking_available', 'parking_type', 'parking_spaces', 'road_conditions',
        'driving_instructions', 'taxi_cost_from_barcelona', 'recommended_transfer_companies',
        'winery_transfer_service', 'bike_routes', 'ebike_rental_nearby', 'cycling_difficulty',
        'tour_operators', 'group_tour_prices', 'partnership_terms', 'commission_rate',
        'partner_offers', 'partnership_contact', 'partnership_programs',
        'logo', 'featured_image', 'gallery', 'video_url', 'virtual_tour_url',
        'languages', 'booking_required', 'nearby_wineries', 'best_visit_time',
        'seasonal_features', 'pets_allowed', 'meta_title', 'meta_description',
        'meta_keywords', 'status', 'featured', 'views_count',
        // Accessibility fields
        'accessibility_wheelchair_parking', 'accessibility_wheelchair_entrance',
        'accessibility_wheelchair_restroom', 'accessibility_wheelchair_seating',
        // Payment fields
        'payment_credit_cards', 'payment_debit_cards', 'payment_nfc_mobile', 'payment_cash',
        // Amenities fields
        'amenities_outdoor_seating', 'amenities_dog_friendly', 'amenities_family_friendly',
        'amenities_groups', 'amenities_wine_bar', 'amenities_terrace', 'amenities_garden',
        'amenities_picnic_area', 'amenities_playground',
        // Features fields
        'features_sea_views', 'features_mountain_views', 'features_vineyard_views',
        'features_historic_building',
        // Specialization fields
        'specialization_cava', 'specialization_organic', 'specialization_biodynamic',
        'specialization_natural_wine',
        // Experience fields
        'experience_guided_tours', 'experience_expert_sommelier', 'experience_educational',
        'experience_harvest_participation',
        // Entertainment fields
        'entertainment_live_music', 'entertainment_cultural_events', 'entertainment_festivals',
        'entertainment_art_exhibitions',
        // Group services
        'groups_hen_stag_parties', 'groups_corporate_events', 'groups_private_parties',
        'groups_team_building',
        // Service fields
        'service_multilingual_guides', 'service_personalized_experience',
        'service_flexible_schedule', 'service_advance_booking_required',
        // Rating fields
        'google_rating', 'google_reviews_count', 'tripadvisor_rating', 'tripadvisor_reviews_count'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Получить винодельни с информацией о регионах
     */
    public function getWithRegion()
    {
        return $this->db->table($this->table)
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.status', 'active')
            ->orderBy('wineries.featured', 'DESC')
            ->orderBy('wineries.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Получить винодельни по региону
     */
    public function getByRegion($regionId)
    {
        return $this->db->table($this->table)
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.region_id', $regionId)
            ->where('wineries.status', 'active')
            ->orderBy('wineries.featured', 'DESC')
            ->orderBy('wineries.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Получить винодельни с фильтрацией
     */
    public function getFiltered($filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.status', 'active');

        // Поиск по названию
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('wineries.name', $filters['search'])
                ->orLike('wineries.short_description', $filters['search'])
                ->orLike('wineries.description', $filters['search'])
                ->groupEnd();
        }

        // Фильтр по региону
        if (!empty($filters['region_id'])) {
            $builder->where('wineries.region_id', $filters['region_id']);
        }

        // Фильтр по типам вина
        if (!empty($filters['wine_type']) && is_array($filters['wine_type'])) {
            $builder->groupStart();
            foreach ($filters['wine_type'] as $wineType) {
                $builder->orLike('wineries.wine_types', $wineType);
            }
            $builder->groupEnd();
        }

        // Фильтр по ценовой категории
        if (!empty($filters['price_category'])) {
            $builder->where('wineries.price_category', $filters['price_category']);
        }

        // Фильтры по услугам
        if (!empty($filters['tours'])) {
            $builder->where('wineries.tours_available', 1);
        }

        if (!empty($filters['tastings'])) {
            $builder->where('wineries.tastings_available', 1);
        }

        if (!empty($filters['restaurant'])) {
            $builder->where('wineries.restaurant_available', 1);
        }

        if (!empty($filters['organic'])) {
            $builder->where('wineries.organic_production', 1);
        }

        // Сортировка
        $builder->orderBy('wineries.featured', 'DESC')
                ->orderBy('wineries.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Получить статистику виноделен
     */
    public function getStats()
    {
        $total = $this->where('status', 'active')->countAllResults();
        
        $withTours = $this->where('status', 'active')
                          ->where('tours_available', 1)
                          ->countAllResults();
        
        $withTastings = $this->where('status', 'active')
                             ->where('tastings_available', 1)
                             ->countAllResults();
        
        $organic = $this->where('status', 'active')
                        ->where('organic_production', 1)
                        ->countAllResults();

        return [
            'total' => $total,
            'with_tours' => $withTours,
            'with_tastings' => $withTastings,
            'organic' => $organic
        ];
    }

    /**
     * Получить уникальные типы вин
     */
    public function getWineTypes()
    {
        $wineries = $this->select('wine_types')
                         ->where('status', 'active')
                         ->where('wine_types IS NOT NULL')
                         ->where('wine_types !=', '')
                         ->findAll();

        $wineTypes = [];
        foreach ($wineries as $winery) {
            if (!empty($winery['wine_types'])) {
                $types = json_decode($winery['wine_types'], true);
                if (is_array($types)) {
                    $wineTypes = array_merge($wineTypes, $types);
                }
            }
        }

        return array_unique($wineTypes);
    }

    /**
     * Получить ценовые категории
     */
    public function getPriceCategories()
    {
        return [
            'budget' => 'Budget (€-€€)',
            'mid-range' => 'Mid-range (€€-€€€)',
            'premium' => 'Premium (€€€-€€€€)'
        ];
    }

    /**
     * Получить популярные винодельни (featured)
     */
    public function getFeatured($limit = 6)
    {
        return $this->db->table($this->table)
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.status', 'active')
            ->where('wineries.featured', 1)
            ->orderBy('wineries.name', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Поиск виноделен рядом с указанными координатами
     */
    public function getNearby($latitude, $longitude, $radius = 50, $limit = 5)
    {
        // Простой поиск по радиусу (для более точного поиска можно использовать формулу haversine)
        $latRange = $radius / 111; // примерно 111 км в 1 градусе широты
        $lngRange = $radius / (111 * cos(deg2rad($latitude)));

        return $this->db->table($this->table)
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.status', 'active')
            ->where('wineries.latitude >=', $latitude - $latRange)
            ->where('wineries.latitude <=', $latitude + $latRange)
            ->where('wineries.longitude >=', $longitude - $lngRange)
            ->where('wineries.longitude <=', $longitude + $lngRange)
            ->orderBy('wineries.featured', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Обновить изображения винодельни
     */
    public function updateImages($id, $imageType, $fileName)
    {
        switch ($imageType) {
            case 'featured':
                return $this->update($id, ['featured_image' => $fileName]);
                
            case 'logo':
                return $this->update($id, ['logo' => $fileName]);
                
            case 'gallery':
                $winery = $this->find($id);
                $gallery = json_decode($winery['gallery'] ?? '[]', true);
                $gallery[] = $fileName;
                return $this->update($id, ['gallery' => json_encode($gallery)]);
                
            default:
                return false;
        }
    }

    /**
     * Удалить изображение винодельни
     */
    public function removeImage($id, $imageType, $fileName = null)
    {
        switch ($imageType) {
            case 'featured':
                return $this->update($id, ['featured_image' => null]);
                
            case 'logo':
                return $this->update($id, ['logo' => null]);
                
            case 'gallery':
                $winery = $this->find($id);
                $gallery = json_decode($winery['gallery'] ?? '[]', true);
                if ($fileName) {
                    $gallery = array_filter($gallery, function($img) use ($fileName) {
                        return $img !== $fileName;
                    });
                } else {
                    $gallery = []; // Удалить всю галерею
                }
                return $this->update($id, ['gallery' => json_encode(array_values($gallery))]);
                
            default:
                return false;
        }
    }
}