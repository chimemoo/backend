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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1/api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('me', 'AuthController@me');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('logout', 'AuthController@logout');

    $router->get('challenge', 'ChallengeController@index');
    $router->get('challenge/{id}', 'ChallengeController@get');
    $router->post('challenge', 'ChallengeController@create');
    $router->put('challenge/{id}', 'ChallengeController@update');
    $router->delete('challenge/{id}', 'ChallengeController@delete');

    // NOT READY
    $router->get('challenge/{id}/followers', 'ChallengeFollowerController@get');
    $router->post('challenge/{id}/follow', 'ChallengeFollowerController@follow');
    $router->post('challenge/{id}/unfollow', 'ChallengeFollowerController@unfollow');

    $router->get('challenge/{id}/submissions', 'ChallengeSubmissionController@get');
    $router->post('challenge/{id}/submit', 'ChallengeSubmissionController@submit');
    $router->put('challenge/{id}/submission', 'ChallengeSubmissionController@update');
    $router->delete('challenge/{id}/unsubmit', 'ChallengeSubmissionController@unsubmit');


});
