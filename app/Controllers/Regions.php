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

        $wineries = $wineryModel->where('region_id', $region['id'])
                               ->where('status', 'active')
                               ->findAll();

        $data = [
            'title' => $region['name'] . ' Wineries - Barcelona Wineries',
            'region' => $region,
            'wineries' => $wineries,
            'regions' => $regionModel->getActive()
        ];

        return view('regions/detail', $data);
    }
}