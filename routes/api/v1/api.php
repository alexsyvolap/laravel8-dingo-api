<?php
use Dingo\Api\Routing\Router;

$api = app(Router::class);

$api->version('v1', ['as' => 'v1'], function ($api) {
    $api->group(['as' => 'auth', 'prefix' => 'auth', 'namespace' => 'App\Api\v1\Controllers\Auth'], function($api) {
        $api->post('login', 'AuthController@login');
        $api->post('logout', 'AuthController@logout');
        $api->post('refresh', 'AuthController@refresh');
        $api->post('me', 'AuthController@me');
    });
    
    $api->group(['namespace' => 'App\Api\v1\Controllers\User', 'as' => 'users', 'prefix' => 'users', 'middleware' => 'api.auth'], function($api) {
        $api->get('/', 'UserController@index');
        $api->get('/{id}', ['as' => 'show', 'uses' => 'UserController@show']);
        $api->post('/', 'UserController@store');
        $api->put('/', 'UserController@edit');
        $api->delete('/{id}', 'UserController@destroy');
    });
});