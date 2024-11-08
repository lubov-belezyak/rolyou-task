<?php

use Src\Services\Router;

$router = new Router();

$router->get('/get', 'UserController@index');
$router->post('/create', 'UserController@store');
$router->get('/get/{id}', 'UserController@show');
$router->patch('/update/{id}', 'UserController@update');
$router->delete('/delete/{id}', 'UserController@delete');
$router->delete('/delete', 'UserController@deleteAll');


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->resolve($uri, $method);
