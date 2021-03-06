<?php

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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Route::resource('threads', 'ThreadsController');
Route::get('/threads', 'ThreadsController@index')
	->name('threads.index');

Route::get('/threads/create', 'ThreadsController@create')
	->name('threads.create');

Route::get('/threads/{channel}', 'ThreadsController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')
	->name('threads.show');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy')
	->name('threads.destroy');

Route::post('/threads', 'ThreadsController@store')->name('threads.store');

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')
	->name('add-reply');

Route::delete('/replies/{reply}', 'RepliesController@destroy')
	->name('replies.destroy');

Route::patch('/replies/{reply}', 'RepliesController@update')
	->name('replies.update');
	
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')
	->name('favorite');

Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')
	->name('profiles.show');

