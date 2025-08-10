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
        
        // Получаем винодельню с информацией о регионе
        $winery = $wineryModel->db->table('wineries')
            ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.slug', $slug)
            ->where('wineries.status', 'active')
            ->get()
            ->getRowArray();
        
        if (!$winery) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Увеличиваем счетчик просмотров
        $wineryModel->update($winery['id'], [
            'views_count' => ($winery['views_count'] ?? 0) + 1
        ]);
        $winery['views_count'] = ($winery['views_count'] ?? 0) + 1;

        // Декодируем JSON поля, если они есть
        $jsonFields = ['wine_types', 'grape_varieties', 'languages', 'gallery'];
        foreach ($jsonFields as $field) {
            if (!empty($winery[$field]) && is_string($winery[$field])) {
                $decoded = json_decode($winery[$field], true);
                $winery[$field] = is_array($decoded) ? $decoded : [];
            } else {
                $winery[$field] = [];
            }
        }

        // Получаем похожие винодельни из того же региона
        $relatedWineries = [];
        if (!empty($winery['region_id'])) {
            $relatedWineries = $wineryModel->db->table('wineries')
                ->select('wineries.*, regions.name as region_name, regions.slug as region_slug')
                ->join('regions', 'regions.id = wineries.region_id', 'left')
                ->where('wineries.region_id', $winery['region_id'])
                ->where('wineries.slug !=', $slug)
                ->where('wineries.status', 'active')
                ->orderBy('wineries.featured', 'DESC')
                ->orderBy('wineries.name', 'ASC')
                ->limit(3)
                ->get()
                ->getResultArray();

            // Декодируем JSON поля для связанных виноделен
            foreach ($relatedWineries as &$related) {
                foreach ($jsonFields as $field) {
                    if (!empty($related[$field]) && is_string($related[$field])) {
                        $decoded = json_decode($related[$field], true);
                        $related[$field] = is_array($decoded) ? $decoded : [];
                    } else {
                        $related[$field] = [];
                    }
                }
            }
        }

        // Формируем SEO данные
        $metaDescription = !empty($winery['meta_description']) 
            ? $winery['meta_description']
            : (!empty($winery['short_description']) 
                ? mb_substr($winery['short_description'], 0, 155) . '...'
                : $winery['name'] . ' - Premium winery in ' . ($winery['region_name'] ?? 'Barcelona region') . '. Wine tours, tastings and experiences.');

        $metaTitle = !empty($winery['meta_title']) 
            ? $winery['meta_title']
            : $winery['name'] . ' - ' . ($winery['region_name'] ?? 'Barcelona') . ' Winery | Barcelona Wineries';

        $metaKeywords = !empty($winery['meta_keywords'])
            ? $winery['meta_keywords']
            : $this->generateMetaKeywords($winery);

        $data = [
            'title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords,
            'winery' => $winery,
            'related_wineries' => $relatedWineries,
            'regions' => $regionModel->getActive()
        ];

        return view('wineries/detail', $data);
    }

    /**
     * Генерирует meta keywords на основе данных винодельни
     */
    private function generateMetaKeywords($winery)
    {
        $keywords = [
            $winery['name'],
            'winery',
            'wine tasting',
            'Barcelona'
        ];

        if (!empty($winery['region_name'])) {
            $keywords[] = $winery['region_name'];
            $keywords[] = $winery['region_name'] . ' wineries';
        }

        if (!empty($winery['wine_types']) && is_array($winery['wine_types'])) {
            foreach ($winery['wine_types'] as $type) {
                $keywords[] = $type . ' wine';
            }
        }

        if (!empty($winery['grape_varieties']) && is_array($winery['grape_varieties'])) {
            $keywords = array_merge($keywords, array_slice($winery['grape_varieties'], 0, 3));
        }

        if ($winery['organic_production']) {
            $keywords[] = 'organic wine';
            $keywords[] = 'organic winery';
        }

        if ($winery['tours_available']) {
            $keywords[] = 'wine tours';
            $keywords[] = 'vineyard tours';
        }

        if ($winery['tastings_available']) {
            $keywords[] = 'wine tasting';
            $keywords[] = 'wine degustation';
        }

        if ($winery['family_winery']) {
            $keywords[] = 'family winery';
            $keywords[] = 'boutique winery';
        }

        if ($winery['restaurant_available']) {
            $keywords[] = 'wine restaurant';
            $keywords[] = 'wine pairing';
        }

        if ($winery['accommodation_available']) {
            $keywords[] = 'wine hotel';
            $keywords[] = 'winery accommodation';
        }

        if ($winery['events_weddings']) {
            $keywords[] = 'wine weddings';
            $keywords[] = 'vineyard events';
        }

        // Добавляем ценовую категорию
        if (!empty($winery['price_category'])) {
            switch ($winery['price_category']) {
                case 'budget':
                    $keywords[] = 'affordable wine';
                    $keywords[] = 'budget winery';
                    break;
                case 'premium':
                    $keywords[] = 'premium wine';
                    $keywords[] = 'luxury winery';
                    break;
                default:
                    $keywords[] = 'quality wine';
                    break;
            }
        }

        // Добавляем транспортные ключевые слова
        if (!empty($winery['drive_time_from_barcelona'])) {
            $keywords[] = 'day trip from Barcelona';
            $keywords[] = 'Barcelona wine tour';
        }

        // Убираем дубликаты, пустые значения и ограничиваем длину
        $keywords = array_filter(array_unique($keywords), function($keyword) {
            return !empty(trim($keyword)) && mb_strlen($keyword) <= 50;
        });
        
        // Ограничиваем общую длину до 500 символов
        $keywordString = implode(', ', $keywords);
        if (mb_strlen($keywordString) > 500) {
            $keywords = array_slice($keywords, 0, floor(count($keywords) * 0.7));
            $keywordString = implode(', ', $keywords);
        }
        
        return $keywordString;
    }

    /**
     * AJAX эндпоинт для получения информации о винодельне (для карт, виджетов и т.д.)
     */
    public function getWineryInfo($slug)
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $wineryModel = new WineryModel();
        
        $winery = $wineryModel->db->table('wineries')
            ->select('wineries.id, wineries.name, wineries.slug, wineries.short_description, 
                      wineries.latitude, wineries.longitude, wineries.phone, wineries.website,
                      wineries.featured_image, wineries.tours_available, wineries.tastings_available,
                      wineries.restaurant_available, wineries.price_category, wineries.wine_types,
                      regions.name as region_name')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.slug', $slug)
            ->where('wineries.status', 'active')
            ->get()
            ->getRowArray();

        if (!$winery) {
            return $this->response->setJSON(['error' => 'Winery not found'], 404);
        }

        // Декодируем wine_types
        if (!empty($winery['wine_types'])) {
            $winery['wine_types'] = json_decode($winery['wine_types'], true) ?: [];
        }

        // Формируем URL изображения
        if (!empty($winery['featured_image'])) {
            $winery['featured_image_url'] = base_url('uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image']);
        } else {
            $winery['featured_image_url'] = null;
        }

        return $this->response->setJSON($winery);
    }

    /**
     * Поиск виноделен для автодополнения
     */
    public function search()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $query = $this->request->getGet('q');
        if (empty($query) || mb_strlen($query) < 2) {
            return $this->response->setJSON(['results' => []]);
        }

        $wineryModel = new WineryModel();
        
        $wineries = $wineryModel->db->table('wineries')
            ->select('wineries.id, wineries.name, wineries.slug, wineries.short_description,
                      regions.name as region_name')
            ->join('regions', 'regions.id = wineries.region_id', 'left')
            ->where('wineries.status', 'active')
            ->groupStart()
                ->like('wineries.name', $query)
                ->orLike('wineries.short_description', $query)
                ->orLike('regions.name', $query)
            ->groupEnd()
            ->orderBy('wineries.featured', 'DESC')
            ->orderBy('wineries.name', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $results = [];
        foreach ($wineries as $winery) {
            $results[] = [
                'id' => $winery['id'],
                'name' => $winery['name'],
                'slug' => $winery['slug'],
                'region' => $winery['region_name'],
                'description' => mb_substr($winery['short_description'] ?? '', 0, 100) . '...',
                'url' => base_url('winery/' . $winery['slug'])
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }
}