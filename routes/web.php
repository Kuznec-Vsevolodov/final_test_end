<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chat/{id}', 'ChatController@index');
Route::get('/users/top-users', 'TopListController@getList');
Route::post('/game/team', 'GameController@teamGame');
Route::post('/game/sing', 'GameController@singGame');
Route::post('/game/id-defender', 'GameController@idDefender');

Route::post('/chat/sing-send', 'ChatController@sendSingMessage');
Route::post('/chat/team-send', 'ChatController@sendTeamMessage');
Route::post('/chat/sing-get', 'ChatController@getSingMessages');
Route::post('/chat/team-get', 'ChatController@getTeamMessages');
Route::post('/chat/sing-user-def', 'ChatController@getSingMainUser');
Route::post('/chat/team-user-def', 'ChatController@getTeamMainUser');
Route::post('/chat/type-def', 'ChatController@getChatType');
Route::post('/chat/sing-positive', 'ChatController@singPositiveResult');
Route::post('/chat/team-positive', 'ChatController@teamPositiveResult');
Route::post('/chat/sing-negative', 'ChatController@singNegativeResult');
Route::post('/chat/team-negative', 'ChatController@TeamNegativeResult');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
