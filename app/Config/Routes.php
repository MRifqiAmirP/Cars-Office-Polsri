<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// PAGE ROUTES
$routes->get('/', 'Page::index');
$routes->get('/login', 'Page::login');
$routes->get('/calendar', 'Page::calendar');

// AUTH ROUTES
$routes->post('/login', 'Auth::auth');
$routes->get('/logout', 'Auth::logout');