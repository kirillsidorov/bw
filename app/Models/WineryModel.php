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
        'wine_shop', 'restaurant_available', 'accommodation_available',
        'events_weddings', 'kids_programs', 'disabled_access',
        'founded_year', 'vineyard_size', 'grape_varieties', 'wine_types',
        'price_category', 'organic_production', 'family_winery',
        'drive_time_from_barcelona', 'parking_available', 'parking_type',
        'languages', 'booking_required', 'meta_title', 'meta_description',
        'status', 'featured', 'featured_image', 'gallery', 'logo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Safely decode JSON field
     */
    private function safeJsonDecode($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value) && !empty($value) && $value !== '[]') {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        
        return [];
    }

    /**
     * Process JSON fields to ensure they're arrays
     */
    private function processJsonFields($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $jsonFields = ['wine_types', 'grape_varieties', 'languages', 'gallery'];
        
        foreach ($jsonFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->safeJsonDecode($data[$field]);
            } else {
                $data[$field] = [];
            }
        }

        return $data;
    }

    /**
     * Get wineries with region information
     */
    public function getWithRegion()
    {
        try {
            $results = $this->db->table($this->table)
                ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
                ->join('regions', 'regions.id = wineries.region_id', 'left')
                ->where('wineries.status', 'active')
                ->orderBy('wineries.featured', 'DESC')
                ->orderBy('wineries.name', 'ASC')
                ->get()
                ->getResultArray();

            // Process JSON fields for each result
            foreach ($results as &$result) {
                $result = $this->processJsonFields($result);
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error in getWithRegion: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single winery with region info by slug
     */
    public function getBySlugWithRegion($slug)
    {
        try {
            $result = $this->db->table($this->table)
                ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
                ->join('regions', 'regions.id = wineries.region_id', 'left')
                ->where('wineries.slug', $slug)
                ->where('wineries.status', 'active')
                ->get()
                ->getRowArray();

            if ($result) {
                $result = $this->processJsonFields($result);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error in getBySlugWithRegion: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get wineries by region with processed JSON fields
     */
    public function getByRegion($regionId)
    {
        try {
            $results = $this->db->table($this->table)
                ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
                ->join('regions', 'regions.id = wineries.region_id', 'left')
                ->where('wineries.region_id', $regionId)
                ->where('wineries.status', 'active')
                ->orderBy('wineries.featured', 'DESC')
                ->orderBy('wineries.name', 'ASC')
                ->get()
                ->getResultArray();

            // Process JSON fields for each result
            foreach ($results as &$result) {
                $result = $this->processJsonFields($result);
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error in getByRegion: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get filtered wineries with search and filters
     */
    public function getFilteredWineries($filters = [])
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
                ->join('regions', 'regions.id = wineries.region_id', 'left')
                ->where('wineries.status', 'active');

            // Search filter
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $builder->groupStart()
                    ->like('wineries.name', $search)
                    ->orLike('wineries.description', $search)
                    ->orLike('wineries.short_description', $search)
                    ->groupEnd();
            }

            // Region filter
            if (!empty($filters['region_id'])) {
                $builder->where('wineries.region_id', $filters['region_id']);
            }

            // Price category filter
            if (!empty($filters['price_category'])) {
                $builder->where('wineries.price_category', $filters['price_category']);
            }

            // Services filters
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

            // Wine type filter (JSON field)
            if (!empty($filters['wine_type']) && is_array($filters['wine_type'])) {
                $builder->groupStart();
                foreach ($filters['wine_type'] as $type) {
                    $builder->orLike('wineries.wine_types', $type);
                }
                $builder->groupEnd();
            }

            $results = $builder->orderBy('wineries.featured', 'DESC')
                ->orderBy('wineries.name', 'ASC')
                ->get()
                ->getResultArray();

            // Process JSON fields
            foreach ($results as &$result) {
                $result = $this->processJsonFields($result);
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error in getFilteredWineries: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get statistics for the homepage
     */
    public function getStats()
    {
        try {
            return [
                'total' => $this->where('status', 'active')->countAllResults(),
                'with_tours' => $this->where('status', 'active')->where('tours_available', 1)->countAllResults(),
                'with_tastings' => $this->where('status', 'active')->where('tastings_available', 1)->countAllResults(),
                'organic' => $this->where('status', 'active')->where('organic_production', 1)->countAllResults(),
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error in getStats: ' . $e->getMessage());
            return [
                'total' => 0,
                'with_tours' => 0,
                'with_tastings' => 0,
                'organic' => 0,
            ];
        }
    }

    /**
     * Get unique wine types for filters
     */
    public function getWineTypes()
    {
        try {
            $results = $this->select('wine_types')
                ->where('status', 'active')
                ->where('wine_types IS NOT NULL')
                ->where('wine_types !=', '[]')
                ->where('wine_types !=', '')
                ->findAll();

            $types = [];
            foreach ($results as $result) {
                if (isset($result['wine_types'])) {
                    $wineTypes = $this->safeJsonDecode($result['wine_types']);
                    if (!empty($wineTypes)) {
                        $types = array_merge($types, $wineTypes);
                    }
                }
            }

            return array_values(array_unique(array_filter($types)));
        } catch (\Exception $e) {
            log_message('error', 'Error in getWineTypes: ' . $e->getMessage());
            return ['red', 'white', 'rosé', 'sparkling']; // Fallback values
        }
    }

    /**
     * Get price categories for filters
     */
    public function getPriceCategories()
    {
        return [
            'budget' => 'Budget (€)',
            'mid-range' => 'Mid-range (€€)',
            'premium' => 'Premium (€€€)',
            'luxury' => 'Luxury (€€€€)'
        ];
    }
}