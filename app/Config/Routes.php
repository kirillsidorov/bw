<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Test routes
$routes->get('/test/database', 'Test::database');

// Debug routes
$routes->get('/image-debug', 'ImageDebug::index');
$routes->post('/image-debug/create-directories', 'ImageDebug::createDirectories');

// Image management routes
$routes->get('/admin/images/(:segment)', 'ImageUpload::manage/$1');
$routes->post('/image-upload/upload', 'ImageUpload::upload');
$routes->post('/image-upload/delete', 'ImageUpload::delete');

// Static pages
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');
$routes->get('/privacy-policy', 'Pages::privacy');

// Winery pages  
$routes->get('/winery/(:segment)', 'Wineries::detail/$1');

// Region pages (should be last to avoid conflicts)
$routes->get('/(:segment)', 'Regions::detail/$1');