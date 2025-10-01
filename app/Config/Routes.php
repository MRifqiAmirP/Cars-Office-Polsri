<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// PAGE ROUTES
$routes->get('/', 'Page::index', ['filter' => 'auth']);
$routes->get('/login', 'Page::login');
$routes->get('/calendar', 'Page::calendar');

// AUTH ROUTES
$routes->group('auth', function($routes) {
    $routes->post('login', 'Auth::login');
    $routes->post('logout','Auth::logout');
    $routes->get('me', 'Auth::me', ['filter' => 'auth']);
});

$routes->group('user', ['filter' => ['auth', 'refreshSession']], function($routes){
    $routes->get('', 'User::index');
    $routes->get('(:num)', 'User::show/$1');
    $routes->post('create', 'User::create');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->delete('(:num)', 'User::delete/$1');
});