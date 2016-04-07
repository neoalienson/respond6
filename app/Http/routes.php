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

// the users route should load the angular2 app
$app->get('users', function () {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the files route should load the angular2 app
$app->get('files', function () {
    $public = rtrim(app()->basePath('public/index.html'), '/');

    return file_get_contents($public);
});

// the menus route should load the angular2 app
$app->get('menus', function () {
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
$app->post('/api/users/forgot', 'UserController@forgot');
$app->post('/api/users/reset', 'UserController@reset');

// users
$app -> get('/api/users/list', ['middleware' => 'jwtauth', 'uses'=> 'UserController@listAll']);
$app -> post('/api/users/edit', ['middleware' => 'jwtauth', 'uses'=> 'UserController@edit']);
$app -> post('/api/users/add', ['middleware' => 'jwtauth', 'uses'=> 'UserController@add']);
$app -> post('/api/users/remove', ['middleware' => 'jwtauth', 'uses'=> 'UserController@remove']);

// pages
$app -> get('/api/pages/list', ['middleware' => 'jwtauth', 'uses'=> 'PageController@listAll']);
$app -> post('/api/pages/add', ['middleware' => 'jwtauth', 'uses'=> 'PageController@add']);
$app -> get('/api/routes/list', ['middleware' => 'jwtauth', 'uses'=> 'PageController@listRoutes']);
$app -> post('/api/pages/save', ['middleware' => 'jwtauth', 'uses'=> 'PageController@save']);
$app -> post('/api/pages/add', ['middleware' => 'jwtauth', 'uses'=> 'PageController@add']);
$app -> post('/api/pages/remove', ['middleware' => 'jwtauth', 'uses'=> 'PageController@remove']);
$app -> post('/api/pages/settings', ['middleware' => 'jwtauth', 'uses'=> 'PageController@settings']);

// files
$app -> post('/api/images/add', ['middleware' => 'jwtauth', 'uses'=> 'FileController@upload']);
$app -> post('/api/files/add', ['middleware' => 'jwtauth', 'uses'=> 'FileController@upload']);
$app -> get('/api/images/list', ['middleware' => 'jwtauth', 'uses'=> 'FileController@listImages']);
$app -> get('/api/files/list', ['middleware' => 'jwtauth', 'uses'=> 'FileController@listFiles']);
$app -> post('/api/files/remove', ['middleware' => 'jwtauth', 'uses'=> 'FileController@remove']);

// menus
$app -> get('/api/menus/list', ['middleware' => 'jwtauth', 'uses'=> 'MenuController@listAll']);
$app -> post('/api/menus/add', ['middleware' => 'jwtauth', 'uses'=> 'MenuController@add']);
$app -> post('/api/menus/edit', ['middleware' => 'jwtauth', 'uses'=> 'MenuController@edit']);
$app -> post('/api/menus/remove', ['middleware' => 'jwtauth', 'uses'=> 'MenuController@remove']);

// menu items
$app -> get('/api/menus/items/list/{id}', ['middleware' => 'jwtauth', 'uses'=> 'MenuItemController@listAll']);
$app -> post('/api/menus/items/add', ['middleware' => 'jwtauth', 'uses'=> 'MenuItemController@add']);
$app -> post('/api/menus/items/edit', ['middleware' => 'jwtauth', 'uses'=> 'MenuItemController@edit']);
$app -> post('/api/menus/items/remove', ['middleware' => 'jwtauth', 'uses'=> 'MenuItemController@remove']);
$app -> post('/api/menus/items/order', ['middleware' => 'jwtauth', 'uses'=> 'MenuItemController@updateOrder']);
