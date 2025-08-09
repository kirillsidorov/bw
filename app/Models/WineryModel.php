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
        'status', 'featured'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // УБИРАЕМ $casts - он вызывает ошибку

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
}