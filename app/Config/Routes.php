<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Customers Page
$routes->get('/', [\App\Controllers\Home::class, 'index']);
$routes->get('/customers', [\App\Controllers\CustomersController::class, 'index']);
$routes->post('/customers/store', [\App\Controllers\CustomersController::class, 'store']);
$routes->get('/customers/edit/(:num)', [\App\Controllers\CustomersController::class, 'edit']);
$routes->post('/customers/update/(:num)', [\App\Controllers\CustomersController::class, 'update']);
$routes->post('/customers/delete/(:num)', [\App\Controllers\CustomersController::class, 'delete']);

// Vehicles Page
$routes->get('/vehicles', [\App\Controllers\VehiclesController::class, 'index']);
$routes->post('/vehicles/add', [\App\Controllers\VehiclesController::class, 'add']);
$routes->get('vehicles/edit/(:num)', [\App\Controllers\VehiclesController::class, 'edit']);
$routes->post('vehicles/update', [\App\Controllers\VehiclesController::class, 'update']);
