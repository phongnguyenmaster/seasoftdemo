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
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('user', 'UserController');

Route::get('auth/social/{socialType}', 'Auth\LoginController@redirectToSocial');
Route::get('auth/social/callback/{socialType}', 'Auth\LoginController@handleSocialCallback');

Route::get('/chat', function() {
    return view('chat');
})->middleware('auth');

Route::get('/getUserLogin', function() {
	return Auth::user();
})->middleware('auth');

Route::get('/messages', function() {
    return App\Message::with('user')-> get();
})->middleware('auth');

Route::post('/messages', function() {
   $user = Auth::user();

  $message = new App\Message();
  $message->message = request()->get('message', '');
  $message->user_id = $user->id;
  $message->save();

  return ['message' => $message->load('user')];
})->middleware('auth');



