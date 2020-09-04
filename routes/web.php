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

// Main view
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

// Load info user login
Route::resource('user', 'UserController');
Route::post('getUserInfo', 'UserController@getUserInfo');
Route::get('/getUserLogin', function () {
    return Auth::user();
})->middleware('auth');

Route::get('loadlistuser/{page}', 'UserController@getListUser');

// Public room
Route::get('loadmessageroom/{lastIdHistory}', 'MessageController@getMessageChatRoom');

// Private Room
Route::get('getPrivateKey/{receiver_id}', 'MessageController@getPrivateKey');
Route::post('loadMessagePrivate', 'MessageController@loadMessagePrivate');

// Login
Route::get('auth/social/{socialType}', 'Auth\LoginController@redirectToSocial');
Route::get('auth/social/callback/{socialType}', 'Auth\LoginController@handleSocialCallback');


// Create new message
Route::post('/newMessages', function () {
    $user = Auth::user();
    $message = new App\Message();
    $message->message = request()->get('message', '');
    $message->private_key = request()->get('privateKey', 0);;
    $message->user_id = $user->id;
    $message->save();
    return ['message' => $message->load('user')];
})->middleware('auth');
