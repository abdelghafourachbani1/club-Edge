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

//articles
$router->get('/clubs/{id}/articles', 'App\\Controllers\\ArticleController@index');
$router->get('/articles/create','App\\Controllers\\ArticleController@create');
$router->post('/articles/store','App\\Controllers\\ArticleController@store');
$router->get('/articles/{id}/edit','App\\Controllers\\ArticleController@edit');
$router->post('/articles/{id}/update','App\\Controllers\\ArticleController@update');
$router->post('/articles/{id}/delete','App\\Controllers\\ArticleController@delete');
$router->get('/articles/{id}/reviews', 'App\\Controllers\\ReviewController@index');
$router->post('/articles/{id}/reviews/store', 'App\\Controllers\\ReviewController@store');
$router->post('/reviews/{id}/delete','App\\Controllers\\ReviewController@delete');

///detail
$router->get('/articles/{id}','App\\Controllers\\ArticleController@show');
$router->get('/admin/example', 'App\\Controllers\\AdminController@example');
// ->middleware('role:admin');