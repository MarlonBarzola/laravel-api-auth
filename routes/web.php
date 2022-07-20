<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;

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
    echo "<center> Welcome </center>";
});

$router->get('/version', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    
    $router->get('profile', 'UserController@profile');

    //get one user by id
    $router->get('users/{id}', 'UserController@singleUser');
    $router->get('users', 'UserController@allUsers');
    
    //Pets
    $router->get('pets', 'PetController@index');
    $router->post('pet/create', 'PetController@create');
    $router->get('pet/{id}', 'PetController@show');
    $router->put('pet/{id}', 'PetController@update');
    $router->delete('pet/{id}/', 'PetController@destroy');

    //Appointments
    $router->get('appointments', 'AppointmentController@index');
    $router->post('appointment/create', 'AppointmentController@create');
    $router->get('appointment/{id}', 'AppointmentController@show');
    $router->put('appointment/{id}', 'AppointmentController@update');
    $router->delete('appointment/{id}/', 'AppointmentController@destroy');

});

