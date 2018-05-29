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

//US.1
Route::get('/', 'WelcomeController@index');

//US.2 e US.3
Auth::routes();
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//US.5 e US.6
Route::get('/users', 'UserController@list')->name("users.list");

//US.7
Route::patch('/users/{user}/block', 'UserController@blockUser')->name('users.block');
Route::patch('/users/{user}/unblock', 'UserController@unblockUser')->name('users.unblock');
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->name('users.promote');
Route::patch('/users/{user}/demote', 'UserController@demoteUser')->name('users.demote');

//US.9
Route::get('/me/password','Auth\ResetPasswordController@changePassowordForm')->name('users.changePasswordForm');
Route::patch('/me/password', 'UserController@changePassword') ->name('users.changePassword');

//US.10
Route::get('/me/profile', 'UserController@showEditMyProfile')->name('users.showEditProfile');
Route::put('/me/profile', 'UserController@editMyProfile')->name('users.editProfile');

//US.11
Route::get('/profiles', 'UserController@getProfiles')->name('users.profiles');

//US.12
Route::get('/me/associates', 'UserController@getAssociates')->name('users.associates');

//US.13
Route::get('/me/associate-of', 'UserController@getAssociate_of')->name('users.associate_of');

//US.14
Route::get('/accounts/{user}', 'AccountController@getUserAccounts')->name('accounts.users');
Route::get('/accounts/{user}/opened', 'AccountController@getUserAccountsOpen')->name('accountsOpen.users');
Route::get('/accounts/{user}/closed', 'AccountController@getUserAccountsClose')->name('accountsClose.users');

//US.15
Route::delete('/account/{account}', 'AccountController@deleteAccount')->name('account.delete');
Route::patch('/account/{account}/close', 'AccountController@closeAccount')->name('account.close');

//US.16
Route::patch('/account/{account}/reopen', 'AccountController@reopenAccount')->name('account.reopen');

//US.20
Route::get('/movements/{account}','MovementController@listMovements')->name('movement.list');

//US.21
Route::get('/movements/{account}/create','MovementController@createMovement')->name('movement.create');
Route::post('/movements/{account}/create','MovementController@showCreateMovement')->name('movement.showCreateMovement');
Route::get('/movement/{movement}','MovementController@editMovement')->name('movement.edit');
Route::put('/movement/{movement}','MovementController@updateMovement')->name('movement.update');
Route::delete('/movement/{movement}','MovementController@destroyMovement')->name('movement.destroy');


