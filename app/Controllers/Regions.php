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

        // Получаем винодельни этого региона
        $wineries = $wineryModel->getByRegion($region['id']);
        
        // Декодируем JSON поля для виноделен
        $jsonFields = ['wine_types', 'grape_varieties', 'languages', 'gallery'];
        foreach ($wineries as &$winery) {
            foreach ($jsonFields as $field) {
                if (!empty($winery[$field]) && is_string($winery[$field])) {
                    $decoded = json_decode($winery[$field], true);
                    $winery[$field] = is_array($decoded) ? $decoded : [];
                } else {
                    $winery[$field] = [];
                }
            }
        }

        // Получаем featured винодельни для отображения в статистике
        $featuredWineries = array_filter($wineries, function($winery) {
            return !empty($winery['featured']);
        });

        // Формируем SEO данные
        $metaTitle = !empty($region['meta_title']) 
            ? $region['meta_title']
            : $region['name'] . ' Wineries - Best Wineries in ' . $region['name'] . ' | Barcelona Wineries';

        $metaDescription = !empty($region['meta_description'])
            ? $region['meta_description']
            : (!empty($region['short_description'])
                ? $region['short_description'] . ' Discover the best wineries in ' . $region['name'] . '.'
                : 'Discover the best wineries in ' . $region['name'] . ' region. Wine tours, tastings and premium wine experiences near Barcelona.');

        $data = [
            'title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $this->generateRegionKeywords($region, $wineries),
            'region' => $region,
            'wineries' => $wineries,
            'featured_wineries' => $featuredWineries,
            'total_wineries' => count($wineries),
            'regions' => $regionModel->getActive()
        ];

        return view('regions/detail', $data);
    }

    /**
     * Генерирует meta keywords для региона
     */
    private function generateRegionKeywords($region, $wineries)
    {
        $keywords = [
            $region['name'],
            $region['name'] . ' wineries',
            $region['name'] . ' wine tours',
            $region['name'] . ' wine tasting',
            'Barcelona wine regions',
            'Catalonia wineries'
        ];

        // Добавляем ключевые слова на основе виноделен в регионе
        $wineTypes = [];
        $hasOrganic = false;
        $hasTours = false;
        $hasTastings = false;

        foreach ($wineries as $winery) {
            if (!empty($winery['wine_types']) && is_array($winery['wine_types'])) {
                $wineTypes = array_merge($wineTypes, $winery['wine_types']);
            }

            if (!empty($winery['organic_production'])) {
                $hasOrganic = true;
            }

            if (!empty($winery['tours_available'])) {
                $hasTours = true;
            }

            if (!empty($winery['tastings_available'])) {
                $hasTastings = true;
            }
        }

        // Добавляем типы вин
        $wineTypes = array_unique($wineTypes);
        foreach (array_slice($wineTypes, 0, 5) as $type) {
            $keywords[] = $type . ' wine ' . $region['name'];
        }

        // Добавляем специфичные ключевые слова
        if ($hasOrganic) {
            $keywords[] = 'organic wineries ' . $region['name'];
        }

        if ($hasTours) {
            $keywords[] = 'vineyard tours ' . $region['name'];
        }

        if ($hasTastings) {
            $keywords[] = 'wine tasting ' . $region['name'];
        }

        // Специфичные ключевые слова для известных регионов
        switch (strtolower($region['name'])) {
            case 'penedès':
                $keywords = array_merge($keywords, ['Cava', 'Cava tasting', 'Codorníu', 'Freixenet', 'sparkling wine']);
                break;
            case 'priorat':
                $keywords = array_merge($keywords, ['DOQ Priorat', 'premium wines', 'slate soils', 'llicorella']);
                break;
            case 'montsant':
                $keywords = array_merge($keywords, ['mountain wineries', 'traditional wines']);
                break;
        }

        // Убираем дубликаты и ограничиваем длину
        $keywords = array_filter(array_unique($keywords), function($keyword) {
            return !empty(trim($keyword)) && mb_strlen($keyword) <= 50;
        });

        $keywordString = implode(', ', $keywords);
        if (mb_strlen($keywordString) > 500) {
            $keywords = array_slice($keywords, 0, floor(count($keywords) * 0.7));
            $keywordString = implode(', ', $keywords);
        }

        return $keywordString;
    }
}