<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─────────────────────────────────────────────
// Web Routes
// ─────────────────────────────────────────────
$routes->get('/', 'HomeController::index');
$routes->get('/map', 'HomeController::map');

// Halaman navigasi WebView (untuk Android user)
$routes->get('/navigate', 'HomeController::navigate');

// ─────────────────────────────────────────────
// API Routes
// ─────────────────────────────────────────────
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Auth
    $routes->post('login', 'AuthController::login');

    // Fasilitas
    $routes->get('fasilitas',          'FasilitasController::index');
    $routes->post('fasilitas',         'FasilitasController::create');
    $routes->get('fasilitas/(:num)',   'FasilitasController::show/$1');
});
