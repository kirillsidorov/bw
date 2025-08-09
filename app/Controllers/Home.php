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

        // Get filters from request
        $filters = [
            'search' => $this->request->getGet('search'),
            'region_id' => $this->request->getGet('region'),
            'price_category' => $this->request->getGet('price_category'),
            'wine_type' => $this->request->getGet('wine_type'),
            'tours' => $this->request->getGet('tours'),
            'tastings' => $this->request->getGet('tastings'),
            'restaurant' => $this->request->getGet('restaurant'),
            'organic' => $this->request->getGet('organic'),
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });

        // Get wineries based on filters
        if (!empty($filters)) {
            $wineries = $wineryModel->getFilteredWineries($filters);
        } else {
            $wineries = $wineryModel->getWithRegion();
        }

        $data = [
            'title' => 'Barcelona Wineries - Discover Premium Wineries Near Barcelona',
            'meta_description' => 'Explore the finest wineries near Barcelona. Wine tours, tastings, and premium experiences in Catalonia\'s beautiful wine regions.',
            'wineries' => $wineries,
            'regions' => $regionModel->getActive(),
            'filters' => $filters,
            'stats' => $wineryModel->getStats(),
            'wine_types' => $wineryModel->getWineTypes(),
            'price_categories' => $wineryModel->getPriceCategories()
        ];

        return view('home/index', $data);
    }
}