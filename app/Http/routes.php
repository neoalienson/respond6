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

// the files route should load the angular2 app
$app->get('files', function () {
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

// handles mirror html
$app->get('/mirror', 'EditController@mirror');

// checks auth status
$app -> get('/api/auth', ['middleware' => 'jwtauth', 'uses'=> 'UserController@auth']);

// test site
$app->get('/api/sites/test', 'SiteController@test');

// validate id
$app->post('/api/sites/validate/id', 'SiteController@validateId');

// create
$app->post('/api/sites/create', 'SiteController@create');

// login
$app->post('/api/users/login', 'UserController@login');

// forgot
$app->post('/api/users/forgot', 'UserController@forgot');

// reset
$app->post('/api/users/reset', 'UserController@reset');

// list pages
$app -> get('/api/pages/list', ['middleware' => 'jwtauth', 'uses'=> 'PageController@listAll']);

// add page
$app -> post('/api/pages/add', ['middleware' => 'jwtauth', 'uses'=> 'PageController@add']);

// list routes
$app -> get('/api/routes/list', ['middleware' => 'jwtauth', 'uses'=> 'PageController@listRoutes']);

// save page
$app -> post('/api/pages/save', ['middleware' => 'jwtauth', 'uses'=> 'PageController@save']);

// add page
$app -> post('/api/pages/add', ['middleware' => 'jwtauth', 'uses'=> 'PageController@add']);

// remove page
$app -> post('/api/pages/remove', ['middleware' => 'jwtauth', 'uses'=> 'PageController@remove']);

// update page settings
$app -> post('/api/pages/settings', ['middleware' => 'jwtauth', 'uses'=> 'PageController@settings']);

// uploads an image
$app -> post('/api/images/add', ['middleware' => 'jwtauth', 'uses'=> 'FileController@upload']);

// uploads a file
$app -> post('/api/files/add', ['middleware' => 'jwtauth', 'uses'=> 'FileController@upload']);

// lists images
$app -> get('/api/images/list', ['middleware' => 'jwtauth', 'uses'=> 'FileController@listImages']);

// lists files
$app -> get('/api/files/list', ['middleware' => 'jwtauth', 'uses'=> 'FileController@listFiles']);
