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
$routes->get('/staff', 'Staff::index', ['filter' => 'auth:staff']);
$routes->get('/user', 'User::index', ['filter' => 'auth:user']);
