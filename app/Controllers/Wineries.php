<?php

namespace App\Controllers;

use App\Models\WineryModel;
use App\Models\RegionModel;

class Wineries extends BaseController
{
    public function detail($slug)
    {
        $wineryModel = new WineryModel();
        $regionModel = new RegionModel();
        
        $winery = $wineryModel->getBySlugWithRegion($slug);
        
        if (!$winery) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get recommended wineries from the same region
        $recommendedWineries = [];
        if ($winery['region_id']) {
            $allRegionWineries = $wineryModel->getByRegion($winery['region_id']);
            
            // Remove current winery and limit to 3 recommendations
            $recommendedWineries = array_filter($allRegionWineries, function($w) use ($winery) {
                return $w['id'] !== $winery['id'];
            });
            $recommendedWineries = array_slice($recommendedWineries, 0, 3);
        }

        $data = [
            'title' => $winery['name'] . ' - Barcelona Wineries',
            'meta_description' => !empty($winery['short_description']) 
                ? $winery['short_description'] 
                : 'Visit ' . $winery['name'] . ' winery near Barcelona. Wine tours, tastings and premium experiences.',
            'winery' => $winery,
            'recommended_wineries' => $recommendedWineries,
            'regions' => $regionModel->getActive()
        ];

        return view('wineries/detail', $data);
    }
}