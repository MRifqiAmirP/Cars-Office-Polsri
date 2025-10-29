<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// MAIN PAGE ROUTES
$routes->get('/', 'Page::index', ['filter' => ['auth', 'asAdmin', 'refreshSession']]);
$routes->get('/login', 'Page::login');

// PAGE ADMIN ROUTES
$routes->group('admin', ['filter' => ['auth', 'asAdmin', 'refreshSession']], function($routes) {
    $routes->group('master', function($routes) {
        $routes->get('user', 'Page::user');
        $routes->get('cars', 'Page::cars');
    });
        $routes->get('request-service', 'Page::request_service');
});

// PAGE USER ROUTES
$routes->group('user', ['filter' => ['auth', 'asUser', 'refreshSession']], function($routes) {
    $routes->get('', 'PageUser::index');
    $routes->get('service', 'PageUser::service');
});

// AUTH ROUTES
$routes->group('auth', function($routes) {
    $routes->post('login', 'API\Auth::login');
    $routes->get('logout','API\Auth::logout');
    $routes->get('me', 'API\Auth::me', ['filter' => 'auth']);
});

$routes->group('master/user', ['filter' => ['auth', 'refreshSession']], function($routes){
    $routes->get('', 'API\User::index');
    $routes->get('(:num)', 'API\User::show/$1');
    $routes->post('create', 'API\User::create');
    $routes->post('update/(:num)', 'API\User::update/$1');
    $routes->delete('(:num)', 'API\User::delete/$1');
});

$routes->group('api', function($routes) {
    $routes->group('cars', function($routes) {
        $routes->get('', 'API\Cars::index');
        $routes->get('(:num)', 'API\Cars::show/$1');
        $routes->post('create', 'API\Cars::create');
        $routes->post('update/(:num)', 'API\Cars::update/$1');
    });

    $routes->group('services', function($routes) {
        $routes->get('', 'API\Service::index');
        $routes->get('(:num)', 'API\Service::show/$1');
        $routes->post('create', 'API\Service::create');
        $routes->post('update/(:num)', 'API\Service::update/$1');
        $routes->get('delete/(:num)', 'API\Service::delete/$1');
    });

    $routes->group('jenis_perawatan', function($routes) {
        $routes->get('', 'API\JenisPerawatan::index');
        $routes->get('(:num)', 'API\JenisPerawatan::show/$1');
        $routes->post('create', 'API\JenisPerawatan::create');
        $routes->post('update/(:num)', 'API\JenisPerawatan::update/$1');
        $routes->get('delete/(:num)', 'API\JenisPerawatan::delete/$1');
    });

    $routes->group('bengkel', function($routes) {
        $routes->get('', 'API\Bengkel::index');
        $routes->get('(:num)', 'API\Bengkel::show/$1');
        $routes->post('create', 'API\Bengkel::create');
        $routes->post('update/(:num)', 'API\Bengkel::update/$1');
    });

    $routes->group('service_request', function($routes) {
        $routes->get('', 'API\ServiceRequest::index');
        $routes->get('(:num)', 'API\ServiceRequest::show/$1');
        $routes->post('create', 'API\ServiceRequest::create');
        $routes->post('update/(:num)', 'API\ServiceRequest::update/$1');
    });

    $routes->group('peminjaman', function($routes) {
        $routes->get('', 'API\Peminjaman::index');
        $routes->get('(:num)', 'API\Peminjaman::show/$1');
        $routes->post('create', 'API\Peminjaman::create');
        $routes->post('update/(:num)', 'API\Peminjaman::update/$1');
    });
});

// DEBUG - GET CSRF TOKEN
$routes->get('/debug/get-csrf', 'API\Api');