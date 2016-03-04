<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// base route should load the angular2 app
$app->get('/', function () use ($app) {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the login route should load the angular2 app
$app->get('login', function () {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the create route should load the angular2 app
$app->get('create', function () {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the login/my-site route should load the angular2 app
$app->get('login/{id}', function ($id) {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the login/my-site route should load the angular2 app
$app->get('forgot/{id}', function ($id) {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the login/my-site route should load the angular2 app
$app->get('reset/{id}/{token}', function ($id) {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// test site
$app->get('/api/site/test', 'SiteController@test');

// test auth
$app->get('/api/site/auth/test', ['middleware' => 'jwtauth', 'SiteController@testAuth']);

// validate id
$app->post('/api/site/validate/id', 'SiteController@validateId');

// login
$app->post('/api/user/login', 'UserController@login');

// forgot
$app->post('/api/user/forgot', 'UserController@forgot');

// reset
$app->post('/api/user/reset', 'UserController@reset');
