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

Route::get('/users', 'UserController@list')->name("users.list");

Route::patch('/users/{user}/block', 'UserController@blockUser')->name('users.block');
Route::patch('/users/{user}/unblock', 'UserController@unblockUser')->name('users.unblock');
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->name('users.promote');
Route::patch('/users/{user}/demote', 'UserController@demoteUser')->name('users.demote');

Route::get('/me/password','Auth\ResetPasswordController@showResetForm')->name('users.changePasswordForm');
Route::patch('/me/password', 'UserController@changePassword') ->name('users.changePassword');

Route::get('/profiles', 'UserController@getProfiles')->name('users.profiles');

Route::get('/me/associates', 'UserController@getAssociates')->name('users.associates');
Route::get('/me/associate_of', 'UserController@getAssociate_of')->name('users.associate_of');

Route::get('/accounts/{user}', 'AccountController@getUserAccounts')->name('accounts.users');
Route::get('/accounts/{user}/opened', 'AccountController@getUserAccountsOpen')->name('accountsOpen.users');
Route::get('/accounts/{user}/closed', 'AccountController@getUserAccountsClose')->name('accountsClose.users');