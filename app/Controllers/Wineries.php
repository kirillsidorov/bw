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
        
        $winery = $wineryModel->where('slug', $slug)
                             ->where('status', 'active')
                             ->first();
        
        if (!$winery) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $winery['name'] . ' - Barcelona Wineries',
            'winery' => $winery,
            'regions' => $regionModel->getActive()
        ];

        return view('wineries/detail', $data);
    }
}