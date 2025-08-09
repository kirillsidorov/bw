<?php

namespace App\Controllers;

use App\Models\WineryModel;
use App\Models\RegionModel;

class Regions extends BaseController
{
    public function detail($slug)
    {
        $regionModel = new RegionModel();
        $wineryModel = new WineryModel();
        
        $region = $regionModel->where('slug', $slug)
                             ->where('status', 'active')
                             ->first();
        
        if (!$region) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $wineries = $wineryModel->getByRegion($region['id']);
        
        // Separate featured wineries
        $featuredWineries = array_filter($wineries, function($w) {
            return $w['featured'] == 1;
        });

        $data = [
            'title' => $region['name'] . ' Wineries - Barcelona Wineries',
            'meta_description' => !empty($region['short_description']) 
                ? $region['short_description'] 
                : 'Discover wineries in ' . $region['name'] . ' near Barcelona. Wine tours, tastings and experiences.',
            'region' => $region,
            'wineries' => $wineries,
            'featured_wineries' => $featuredWineries,
            'total_wineries' => count($wineries),
            'regions' => $regionModel->getActive()
        ];

        return view('regions/detail', $data);
    }
}