<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─────────────────────────────────────────────
// Web Routes
// ─────────────────────────────────────────────
$routes->get('/', 'HomeController::map');
$routes->get('/landing', 'HomeController::index');
$routes->get('/map', 'HomeController::map');
$routes->get('/navigate', 'HomeController::navigate');

// ─────────────────────────────────────────────
// API Routes
// ─────────────────────────────────────────────
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Auth
    $routes->post('login', 'AuthController::login');

    // Kecamatan
    $routes->get('kecamatan',                        'KecamatanController::index');
    $routes->get('kecamatan/(:num)/fasilitas',       'KecamatanController::fasilitas/$1');

    // Fasilitas
    $routes->get('fasilitas',                        'FasilitasController::index');
    $routes->post('fasilitas',                       'FasilitasController::create');
    $routes->get('fasilitas/(:num)',                 'FasilitasController::show/$1');
    $routes->get('fasilitas/(:num)/ratings',         'FasilitasController::ratings/$1');
    $routes->post('fasilitas/(:num)/ratings',        'FasilitasController::createRating/$1');
});
