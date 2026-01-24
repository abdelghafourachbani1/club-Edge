<?php

/** @var Router $router */

// ============================================
// HOME ROUTES
// ============================================
$router->get('/', 'App\\Controllers\\HomeController@home');
$router->get('/home', 'App\\Controllers\\HomeController@home');

$router->get('/test', static function (): void {
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'OK';
});

// ============================================
// EVENT ROUTES
// ============================================

// Public routes
$router->get('/events', 'App\\Controllers\\EventController@index');
$router->get('/events/upcoming', 'App\\Controllers\\EventController@upcoming');
$router->get('/events/past', 'App\\Controllers\\EventController@past');

// President routes
$router->get('/events/create', 'App\\Controllers\\EventController@create')
    ->middleware('role:president');

// Routes with {id}/action
$router->get('/events/{id}/edit', 'App\\Controllers\\EventController@edit')
    ->middleware('role:president');
$router->get('/events/{id}/participants', 'App\\Controllers\\EventController@participants')
    ->middleware('role:president');
$router->get('/events/{id}/reviews', 'App\\Controllers\\ReviewController@index');

// Dynamic routes
$router->get('/events/club/{clubId}', 'App\\Controllers\\EventController@byClub');
$router->get('/events/{id}', 'App\\Controllers\\EventController@show');

// POST routes
$router->post('/events/store', 'App\\Controllers\\EventController@store')
    ->middleware('role:president');
$router->post('/events/{id}/update', 'App\\Controllers\\EventController@update')
    ->middleware('role:president');
$router->post('/events/{id}/delete', 'App\\Controllers\\EventController@delete')
    ->middleware('role:president');
$router->post('/events/{id}/register', 'App\\Controllers\\EventController@register')
    ->middleware('role:student');
$router->post('/events/{id}/unregister', 'App\\Controllers\\EventController@unregister')
    ->middleware('role:student');

// Review routes
$router->post('/events/{id}/reviews/create', 'App\\Controllers\\ReviewController@store')
    ->middleware('role:student');

// ============================================
// AUTH ROUTES
// ============================================
$router->get('/login', 'App\\Controllers\\AuthController@showLogin');
$router->post('/login', 'App\\Controllers\\AuthController@login');
$router->get('/logout', 'App\\Controllers\\AuthController@logout');
$router->get('/register', 'App\\Controllers\\AuthController@showRegister');
$router->post('/register', 'App\\Controllers\\AuthController@register');

$router->post('/login', 'App\\Controllers\\AuthController@login');
