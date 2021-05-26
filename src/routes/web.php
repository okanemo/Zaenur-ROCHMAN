<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->get('/login/{provider}', 'AuthController@redirectToProvider');
$router->get('/login/{provider}/callback', 'AuthController@handleProviderCallback');
$router->post('/register', 'AuthController@register');

$router->group(['middleware' => ['auth']], function () use ($router) {
    $router->get('/users/{id}', 'UserController@show');
    $router->put('/users/{id}/update', 'UserController@update');
});
    //group