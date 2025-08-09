<?php

namespace App\Controllers;

use App\Models\WineryModel;
use App\Models\RegionModel;

class ImageDebug extends BaseController
{
    public function index()
    {
        $wineryModel = new WineryModel();
        $regionModel = new RegionModel();
        
        $wineries = $wineryModel->findAll();
        $regions = $regionModel->findAll();
        
        $imageStatus = [
            'wineries' => [],
            'regions' => [],
            'directories' => $this->checkDirectories(),
            'summary' => [
                'total_wineries' => count($wineries),
                'wineries_with_featured' => 0,
                'wineries_with_gallery' => 0,
                'wineries_with_logo' => 0,
                'missing_featured' => 0,
                'missing_gallery' => 0,
                'missing_logo' => 0
            ]
        ];
        
        // Check winery images
        foreach ($wineries as $winery) {
            $wineryStatus = [
                'id' => $winery['id'],
                'name' => $winery['name'],
                'slug' => $winery['slug'],
                'featured_image' => [
                    'filename' => $winery['featured_image'] ?? null,
                    'exists' => false,
                    'path' => null,
                    'url' => null
                ],
                'gallery' => [
                    'count' => 0,
                    'images' => [],
                    'missing' => []
                ],
                'logo' => [
                    'filename' => $winery['logo'] ?? null,
                    'exists' => false,
                    'path' => null,
                    'url' => null
                ]
            ];
            
            // Check featured image
            if (!empty($winery['featured_image'])) {
                $featuredPath = FCPATH . 'uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image'];
                $wineryStatus['featured_image']['exists'] = file_exists($featuredPath);
                $wineryStatus['featured_image']['path'] = $featuredPath;
                if ($wineryStatus['featured_image']['exists']) {
                    $wineryStatus['featured_image']['url'] = base_url('uploads/wineries/featured/' . $winery['id'] . '/' . $winery['featured_image']);
                    $imageStatus['summary']['wineries_with_featured']++;
                } else {
                    $imageStatus['summary']['missing_featured']++;
                }
            } else {
                $imageStatus['summary']['missing_featured']++;
            }
            
            // Check gallery images
            if (!empty($winery['gallery'])) {
                $gallery = is_string($winery['gallery']) ? json_decode($winery['gallery'], true) : $winery['gallery'];
                if (is_array($gallery)) {
                    $wineryStatus['gallery']['count'] = count($gallery);
                    foreach ($gallery as $image) {
                        $galleryPath = FCPATH . 'uploads/wineries/gallery/' . $winery['id'] . '/' . $image;
                        $imageInfo = [
                            'filename' => $image,
                            'exists' => file_exists($galleryPath),
                            'path' => $galleryPath
                        ];
                        if ($imageInfo['exists']) {
                            $imageInfo['url'] = base_url('uploads/wineries/gallery/' . $winery['id'] . '/' . $image);
                            $wineryStatus['gallery']['images'][] = $imageInfo;
                        } else {
                            $wineryStatus['gallery']['missing'][] = $imageInfo;
                        }
                    }
                    if (!empty($wineryStatus['gallery']['images'])) {
                        $imageStatus['summary']['wineries_with_gallery']++;
                    } else {
                        $imageStatus['summary']['missing_gallery']++;
                    }
                }
            } else {
                $imageStatus['summary']['missing_gallery']++;
            }
            
            // Check logo
            if (!empty($winery['logo'])) {
                $logoPath = FCPATH . 'uploads/wineries/logos/' . $winery['id'] . '/' . $winery['logo'];
                $wineryStatus['logo']['exists'] = file_exists($logoPath);
                $wineryStatus['logo']['path'] = $logoPath;
                if ($wineryStatus['logo']['exists']) {
                    $wineryStatus['logo']['url'] = base_url('uploads/wineries/logos/' . $winery['id'] . '/' . $winery['logo']);
                    $imageStatus['summary']['wineries_with_logo']++;
                } else {
                    $imageStatus['summary']['missing_logo']++;
                }
            } else {
                $imageStatus['summary']['missing_logo']++;
            }
            
            $imageStatus['wineries'][] = $wineryStatus;
        }
        
        // Check region images
        foreach ($regions as $region) {
            $regionStatus = [
                'id' => $region['id'],
                'name' => $region['name'],
                'slug' => $region['slug'],
                'image' => [
                    'filename' => $region['image'] ?? null,
                    'exists' => false,
                    'path' => null,
                    'url' => null
                ]
            ];
            
            if (!empty($region['image'])) {
                $regionPath = FCPATH . 'uploads/regions/' . $region['image'];
                $regionStatus['image']['exists'] = file_exists($regionPath);
                $regionStatus['image']['path'] = $regionPath;
                if ($regionStatus['image']['exists']) {
                    $regionStatus['image']['url'] = base_url('uploads/regions/' . $region['image']);
                }
            }
            
            $imageStatus['regions'][] = $regionStatus;
        }
        
        $data = [
            'title' => 'Image Debug - Barcelona Wineries',
            'imageStatus' => $imageStatus
        ];
        
        return view('debug/images', $data);
    }
    
    private function checkDirectories()
    {
        $directories = [
            'uploads' => FCPATH . 'uploads/',
            'uploads/wineries' => FCPATH . 'uploads/wineries/',
            'uploads/wineries/featured' => FCPATH . 'uploads/wineries/featured/',
            'uploads/wineries/gallery' => FCPATH . 'uploads/wineries/gallery/',
            'uploads/wineries/logos' => FCPATH . 'uploads/wineries/logos/',
            'uploads/regions' => FCPATH . 'uploads/regions/'
        ];
        
        $status = [];
        foreach ($directories as $name => $path) {
            $status[$name] = [
                'path' => $path,
                'exists' => is_dir($path),
                'writable' => is_dir($path) ? is_writable($path) : false,
                'permissions' => is_dir($path) ? substr(sprintf('%o', fileperms($path)), -4) : null
            ];
        }
        
        return $status;
    }
    
    public function createDirectories()
    {
        $directories = [
            FCPATH . 'uploads/',
            FCPATH . 'uploads/wineries/',
            FCPATH . 'uploads/wineries/featured/',
            FCPATH . 'uploads/wineries/gallery/',
            FCPATH . 'uploads/wineries/logos/',
            FCPATH . 'uploads/regions/'
        ];
        
        $created = [];
        $errors = [];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0755, true)) {
                    $created[] = $dir;
                } else {
                    $errors[] = $dir;
                }
            }
        }
        
        return $this->response->setJSON([
            'success' => empty($errors),
            'created' => $created,
            'errors' => $errors
        ]);
    }
}