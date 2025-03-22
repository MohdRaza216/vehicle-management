<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [\App\Controllers\Home::class, 'index']);
$routes->get('/customers', [\App\Controllers\CustomersController::class, 'index']);
$routes->post('/customers/store', [\App\Controllers\CustomersController::class, 'store']);
$routes->get('/customers/edit/(:num)', [\App\Controllers\CustomersController::class, 'edit']);
$routes->post('/customers/update/(:num)', [\App\Controllers\CustomersController::class, 'update']);
$routes->post('/customers/delete/(:num)', [\App\Controllers\CustomersController::class, 'delete']);
