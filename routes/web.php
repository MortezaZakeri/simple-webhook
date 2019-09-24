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


$router->group(['prefix' => 'api', 'middleware' => 'auth'], function ($router) {

    $router->post('webhook/test', 'WebhookController@triggerAll');

    $router->get('webhook/call/[{id}]', 'WebhookController@call');

    $router->get('webhook', 'WebhookController@myWebhooks');

    $router->post('webhook', 'WebhookController@create');

});
