<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/test/database', 'Test::database');

// Страницы регионов
$routes->get('/(:segment)', 'Regions::detail/$1');

// Страницы виноделен  
$routes->get('/winery/(:segment)', 'Wineries::detail/$1');

$routes->get('/admin/images/(:segment)', 'ImageUpload::manage/$1');
$routes->post('/image-upload/upload', 'ImageUpload::upload');
$routes->post('/image-upload/delete', 'ImageUpload::delete');