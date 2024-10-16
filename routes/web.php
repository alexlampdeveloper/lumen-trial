<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('register', 'UserController@register');
        $router->post('sign-in', 'UserController@signIn');
        $router->post('recover-password', 'UserController@recoverPassword');
        $router->patch('recover-password', 'UserController@updatePassword');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('companies', 'CompanyController@index');
            $router->post('companies', 'CompanyController@store');
        });
    });
});
