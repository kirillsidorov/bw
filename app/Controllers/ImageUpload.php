<?php

namespace App\Controllers;

use App\Models\WineryModel;

class ImageUpload extends BaseController
{
    protected $wineryModel;

    public function __construct()
    {
        $this->wineryModel = new WineryModel();
    }

    /**
     * Страница управления изображениями винодельни
     */
    public function manage($winerySlug)
    {
        $winery = $this->wineryModel->where('slug', $winerySlug)->first();
        
        if (!$winery) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Manage Images - ' . $winery['name'],
            'winery' => $winery,
            'existing_images' => $this->getExistingImages($winery)
        ];

        return view('admin/images/manage', $data);
    }

    /**
     * Загрузка изображений с подпапками по ID
     */
    public function upload()
    {
        $winerySlug = $this->request->getPost('winery_slug');
        $imageType = $this->request->getPost('image_type'); // featured, gallery, logo
        
        $winery = $this->wineryModel->where('slug', $winerySlug)->first();
        if (!$winery) {
            return $this->response->setJSON(['success' => false, 'message' => 'Winery not found']);
        }

        $file = $this->request->getFile('image');
        
        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file']);
        }

        // Проверяем тип файла
        if (!in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only JPG, PNG, WEBP files allowed']);
        }

        // Создаем структуру папок с ID винодельни
        $wineryId = $winery['id'];
        $uploadPath = FCPATH . 'uploads/wineries/' . $imageType . '/' . $wineryId . '/';
        
        // Создаем папку если не существует
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Генерируем имя файла
        switch ($imageType) {
            case 'featured':
                $fileName = 'featured_' . time() . '.' . $file->getExtension();
                break;
            case 'logo':
                $fileName = 'logo_' . time() . '.' . $file->getExtension();
                break;
            case 'gallery':
                // Считаем существующие файлы галереи для нумерации
                $existingFiles = glob($uploadPath . 'gallery_*');
                $nextNumber = count($existingFiles) + 1;
                $fileName = 'gallery_' . $nextNumber . '_' . time() . '.' . $file->getExtension();
                break;
            default:
                $fileName = 'image_' . time() . '.' . $file->getExtension();
        }

        // Загружаем файл
        if ($file->move($uploadPath, $fileName)) {
            // Обновляем базу данных
            $result = $this->updateWineryImages($winery['id'], $imageType, $fileName);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Image uploaded successfully',
                    'filename' => $fileName,
                    'path' => $imageType . '/' . $wineryId . '/' . $fileName
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Database update failed']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'File upload failed']);
        }
    }

    /**
     * Удаление изображения из подпапки
     */
    public function delete()
    {
        $winerySlug = $this->request->getPost('winery_slug');
        $imageType = $this->request->getPost('image_type');
        $fileName = $this->request->getPost('filename');
        
        $winery = $this->wineryModel->where('slug', $winerySlug)->first();
        if (!$winery) {
            return $this->response->setJSON(['success' => false, 'message' => 'Winery not found']);
        }

        // Удаляем файл из подпапки ID
        $wineryId = $winery['id'];
        $filePath = FCPATH . 'uploads/wineries/' . $imageType . '/' . $wineryId . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Обновляем базу данных
        $result = $this->removeImageFromWinery($winery['id'], $imageType, $fileName);
        
        return $this->response->setJSON([
            'success' => $result, 
            'message' => $result ? 'Image deleted successfully' : 'Database update failed'
        ]);
    }

    /**
     * Обновление изображений в базе данных
     */
    private function updateWineryImages($wineryId, $imageType, $fileName)
    {
        switch ($imageType) {
            case 'featured':
                return $this->wineryModel->update($wineryId, ['featured_image' => $fileName]);
                
            case 'logo':
                return $this->wineryModel->update($wineryId, ['logo' => $fileName]);
                
            case 'gallery':
                $winery = $this->wineryModel->find($wineryId);
                $gallery = json_decode($winery['gallery'] ?? '[]', true);
                $gallery[] = $fileName;
                return $this->wineryModel->update($wineryId, ['gallery' => json_encode($gallery)]);
                
            default:
                return false;
        }
    }

    /**
     * Удаление изображения из базы данных
     */
    private function removeImageFromWinery($wineryId, $imageType, $fileName)
    {
        switch ($imageType) {
            case 'featured':
                return $this->wineryModel->update($wineryId, ['featured_image' => null]);
                
            case 'logo':
                return $this->wineryModel->update($wineryId, ['logo' => null]);
                
            case 'gallery':
                $winery = $this->wineryModel->find($wineryId);
                $gallery = json_decode($winery['gallery'] ?? '[]', true);
                $gallery = array_filter($gallery, function($img) use ($fileName) {
                    return $img !== $fileName;
                });
                return $this->wineryModel->update($wineryId, ['gallery' => json_encode(array_values($gallery))]);
                
            default:
                return false;
        }
    }

    /**
     * Получение существующих изображений из подпапок ID
     */
    private function getExistingImages($winery)
    {
        $wineryId = $winery['id'];
        $images = [
            'featured' => [],
            'gallery' => [],
            'logo' => []
        ];

        $paths = [
            'featured' => FCPATH . 'uploads/wineries/featured/' . $wineryId . '/',
            'gallery' => FCPATH . 'uploads/wineries/gallery/' . $wineryId . '/',
            'logo' => FCPATH . 'uploads/wineries/logos/' . $wineryId . '/'
        ];

        foreach ($paths as $type => $path) {
            if (is_dir($path)) {
                $files = scandir($path);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..' && is_file($path . $file)) {
                        $images[$type][] = $file;
                    }
                }
                // Сортируем файлы по имени
                sort($images[$type]);
            }
        }

        return $images;
    }

    /**
     * Получение полного пути к изображению
     */
    public function getImagePath($wineryId, $imageType, $fileName)
    {
        return 'uploads/wineries/' . $imageType . '/' . $wineryId . '/' . $fileName;
    }

    /**
     * Получение URL изображения
     */
    public function getImageUrl($wineryId, $imageType, $fileName)
    {
        return base_url($this->getImagePath($wineryId, $imageType, $fileName));
    }
}