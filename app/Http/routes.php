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

// the pages route should load the angular2 app
$app->get('pages', function () {
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

// handles editing
$app->get('/edit', 'EditController@edit');

// checks auth status
$app -> get('/api/auth', ['middleware' => 'jwtauth', 'uses'=> 'UserController@auth']);

// test site
$app->get('/api/sites/test', 'SiteController@test');

// validate id
$app->post('/api/sites/validate/id', 'SiteController@validateId');

// login
$app->post('/api/users/login', 'UserController@login');

// forgot
$app->post('/api/users/forgot', 'UserController@forgot');

// reset
$app->post('/api/users/reset', 'UserController@reset');

// list pages
$app -> get('/api/pages/list', ['middleware' => 'jwtauth', 'uses'=> 'PageController@listAll']);

// save page
$app -> post('/api/pages/save', ['middleware' => 'jwtauth', 'uses'=> 'PageController@save']);
