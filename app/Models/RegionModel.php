<?php

namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'slug', 'description', 'short_description', 'image', 
        'meta_title', 'meta_description', 'sort_order', 'status'
    ];

    public function getActive()
    {
        return $this->where('status', 'active')
                   ->orderBy('sort_order', 'ASC')
                   ->findAll();
    }
}