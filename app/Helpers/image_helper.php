<?php
// app/Helpers/image_helper.php

if (!function_exists('get_winery_image_url')) {
    /**
     * Get winery image URL with fallback check
     */
    function get_winery_image_url($wineryId, $imageType, $filename, $defaultPlaceholder = true)
    {
        if (empty($filename)) {
            return null;
        }

        $imagePath = FCPATH . 'uploads/wineries/' . $imageType . '/' . $wineryId . '/' . $filename;
        
        if (file_exists($imagePath)) {
            return base_url('uploads/wineries/' . $imageType . '/' . $wineryId . '/' . $filename);
        }

        return null;
    }
}

if (!function_exists('winery_image_exists')) {
    /**
     * Check if winery image file exists
     */
    function winery_image_exists($wineryId, $imageType, $filename)
    {
        if (empty($filename)) {
            return false;
        }

        $imagePath = FCPATH . 'uploads/wineries/' . $imageType . '/' . $wineryId . '/' . $filename;
        return file_exists($imagePath);
    }
}

if (!function_exists('get_region_image_url')) {
    /**
     * Get region image URL with fallback check
     */
    function get_region_image_url($filename)
    {
        if (empty($filename)) {
            return null;
        }

        $imagePath = FCPATH . 'uploads/regions/' . $filename;
        
        if (file_exists($imagePath)) {
            return base_url('uploads/regions/' . $filename);
        }

        return null;
    }
}

if (!function_exists('get_winery_gallery_images')) {
    /**
     * Get existing gallery images for a winery
     */
    function get_winery_gallery_images($wineryId, $gallery)
    {
        if (empty($gallery) || !is_array($gallery)) {
            return [];
        }

        $existingImages = [];
        foreach ($gallery as $image) {
            if (winery_image_exists($wineryId, 'gallery', $image)) {
                $existingImages[] = [
                    'filename' => $image,
                    'url' => get_winery_image_url($wineryId, 'gallery', $image)
                ];
            }
        }

        return $existingImages;
    }
}

if (!function_exists('generate_placeholder_svg')) {
    /**
     * Generate SVG placeholder for missing images
     */
    function generate_placeholder_svg($width = 400, $height = 300, $text = 'No Image', $bgColor = '#722f37')
    {
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="100%" height="100%" fill="' . $bgColor . '"/>';
        $svg .= '<text x="50%" y="45%" dominant-baseline="middle" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="18">';
        $svg .= '<tspan x="50%" dy="0">🍷</tspan>';
        $svg .= '<tspan x="50%" dy="25">' . htmlspecialchars($text) . '</tspan>';
        $svg .= '</text>';
        $svg .= '</svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}