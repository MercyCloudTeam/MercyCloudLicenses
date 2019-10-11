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

$router->get('/', "IndexController@index");

$router->post('/activation','LicenseController@activation');
$router->post('/cancel','LicenseController@cancel');
$router->post('/inquire','LicenseController@inquire');
$router->post('/generate','LicenseController@generate');

