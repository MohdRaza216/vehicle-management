<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [\App\Controllers\Home::class, 'index']);
$routes->get('/customers', [\App\Controllers\CustomersController::class, 'index']);
$routes->post('/customers/store', [\App\Controllers\CustomersController::class, 'store']);
