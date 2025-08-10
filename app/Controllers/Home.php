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

        // Получаем фильтры из GET параметров
        $filters = [
            'search' => $this->request->getGet('search'),
            'region_id' => $this->request->getGet('region'),
            'wine_type' => $this->request->getGet('wine_type'),
            'price_category' => $this->request->getGet('price_category'),
            'tours' => $this->request->getGet('tours'),
            'tastings' => $this->request->getGet('tastings'),
            'restaurant' => $this->request->getGet('restaurant'),
            'organic' => $this->request->getGet('organic')
        ];

        // Убираем пустые значения
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });

        // Получаем винодельни с учетом фильтров
        if (!empty($filters)) {
            $wineries = $wineryModel->getFiltered($filters);
        } else {
            // Если фильтров нет, показываем featured винодельни
            $wineries = $wineryModel->getFeatured(12);
            // Если featured мало, дополняем обычными
            if (count($wineries) < 8) {
                $additionalWineries = $wineryModel->getWithRegion();
                // Убираем уже показанные featured
                $featuredIds = array_column($wineries, 'id');
                $additionalWineries = array_filter($additionalWineries, function($winery) use ($featuredIds) {
                    return !in_array($winery['id'], $featuredIds);
                });
                $wineries = array_merge($wineries, array_slice($additionalWineries, 0, 12 - count($wineries)));
            }
        }

        // Декодируем JSON поля для каждой винодельни
        foreach ($wineries as &$winery) {
            $jsonFields = ['wine_types', 'grape_varieties', 'languages', 'gallery'];
            foreach ($jsonFields as $field) {
                if (!empty($winery[$field]) && is_string($winery[$field])) {
                    $winery[$field] = json_decode($winery[$field], true);
                }
            }
        }

        $data = [
            'title' => 'Barcelona Wineries - Discover Premium Wineries Near Barcelona',
            'meta_description' => 'Discover the best wineries near Barcelona. Wine tours, tastings, and premium wine experiences in Catalonia. Find your perfect winery visit today.',
            'meta_keywords' => 'Barcelona wineries, Catalonia wine tours, wine tasting Barcelona, Spanish wineries, Penedès wine region',
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