<?php

/** @var Router $router */

$router->get('/', 'App\\Controllers\\HomeController@home');
$router->get('/home', 'App\\Controllers\\HomeController@home');

$router->get('/test', static function (): void {
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'OK';
});

// Auth
// $router->get('/login', 'App\\Controllers\\AuthController@showLogin');
// $router->post('/login', 'App\\Controllers\\AuthController@login');
// $router->get('/logout', 'App\\Controllers\\AuthController@logout');

// $router->get('/register', 'App\\Controllers\\AuthController@showRegister');
// $router->post('/register', 'App\\Controllers\\AuthController@register');



// Admin
$router->get('/admin/example', 'App\\Controllers\\AdminController@example');
;

