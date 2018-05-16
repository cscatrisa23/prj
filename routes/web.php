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


Route::get('/', function (){
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name("users.list");

Route::patch('/users/{user}/block', 'UserController@blockUser')->name('users.block');
Route::patch('/users/{user}/unblock', 'UserController@unblockUser')->name('users.unblock');
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->name('users.promote');
Route::patch('/users/{user}/demote', 'UserController@demoteUser')->name('users.demote');
