<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Menghubungkan URL ke controller

$routes->get('/', 'AuthController::index');  // awal masuk
$routes->get('/login', 'AuthController::index'); // menampilkan form login
$routes->post('/login', 'AuthController::login'); // proses login
$routes->get('/logout', 'AuthController::logout'); // logout

$routes->get('/admin', 'Admin::index', ['filter' => 'auth:admin']);

// Routes for Services (Admin)
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('services', 'ServiceController::index');
    $routes->get('services/create', 'ServiceController::create');
    $routes->post('services/store', 'ServiceController::store');
    $routes->get('services/edit/(:num)', 'ServiceController::edit/$1');
    $routes->post('services/update/(:num)', 'ServiceController::update/$1');
    $routes->post('services/delete/(:num)', 'ServiceController::delete/$1');
    
    // Routes for Schedules (Admin)
    $routes->get('schedules', 'ScheduleController::index');
    $routes->get('schedules/create', 'ScheduleController::create');
    $routes->post('schedules/store', 'ScheduleController::store');
    $routes->get('schedules/edit/(:num)', 'ScheduleController::edit/$1');
    $routes->post('schedules/update/(:num)', 'ScheduleController::update/$1');
    $routes->post('schedules/delete/(:num)', 'ScheduleController::delete/$1');

    // Routes for Bookings (Admin)
    $routes->get('bookings', 'BookingController::index');
    $routes->get('bookings/create', 'BookingController::create');
    $routes->post('bookings/store', 'BookingController::store');
    $routes->post('bookings/confirm/(:num)', 'BookingController::confirm/$1');
    $routes->post('bookings/cancel/(:num)', 'BookingController::cancel/$1');
    $routes->post('bookings/delete/(:num)', 'BookingController::delete/$1');

    // Routes for Staff (Admin)
    $routes->get('staffs', 'AdminStaffController::index');
    $routes->get('staffs/create', 'AdminStaffController::create');
    $routes->post('staffs/store', 'AdminStaffController::store');
    $routes->get('staffs/edit/(:num)', 'AdminStaffController::edit/$1');
    $routes->post('staffs/update/(:num)', 'AdminStaffController::update/$1');
    $routes->post('staffs/delete/(:num)', 'AdminStaffController::delete/$1');

    // Routes for Users (Admin)
    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users/store', 'UserController::store');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');
});
$routes->get('/staff', 'Staff::index', ['filter' => 'auth:staff']);
$routes->get('/user', 'User::index', ['filter' => 'auth:user']);
