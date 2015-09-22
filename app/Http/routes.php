<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', ['as' => 'main', 'uses' => 'Controller@index']);
$app->get('/{url}', ['as' => 'getLetter', 'uses' => 'Controller@get']);
//$app->post('/', ['as' => 'createLetter', 'uses' => 'Controller@create']);
$app->get('/create', ['as' => 'createLetter', 'uses' => 'Controller@create']);