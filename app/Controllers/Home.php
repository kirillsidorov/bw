<?php

namespace App\Controllers;

use App\Models\WineryModel;
use App\Models\RegionModel;

class Home extends BaseController
{
    public function index()
    {
        $wineryModel = new WineryModel();
        $regionModel = new RegionModel();

        $data = [
            'title' => 'Barcelona Wineries - Discover Premium Wineries Near Barcelona',
            'wineries' => $wineryModel->getWithRegion(),
            'regions' => $regionModel->getActive()
        ];

        return view('home/index', $data);
    }
}