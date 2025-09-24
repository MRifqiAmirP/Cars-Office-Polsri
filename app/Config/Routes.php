<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Page::index');
$routes->get('/login', 'Page::login');
$routes->get('/calendar', 'Page::calendar');

$routes->post('action', 'Auth::action');