<?php

$router->get('/events', 'App\\Controllers\\EventController@index');
$router->get('/events/upcoming', 'App\\Controllers\\EventController@upcoming');
$router->get('/events/past', 'App\\Controllers\\EventController@past');

$router->get('/events/create', 'App\\Controllers\\EventController@create')
    ->middleware('role:president');

$router->get('/events/club/{clubId}', 'App\\Controllers\\EventController@byClub');
$router->get('/events/{id}', 'App\\Controllers\\EventController@show');
$router->get('/events/{id}/edit', 'App\\Controllers\\EventController@edit')
    ->middleware('role:president');
$router->get('/events/{id}/participants', 'App\\Controllers\\EventController@participants')
    ->middleware('role:president');

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