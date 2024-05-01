<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Route group with middlewares
 */

 Route::middleware(['auth'])->group(function () {
    // resource routes for Roles
     Route::resource('roles',RoleController::class);
    // resource routes for Users
     Route::resource('users',UserController::class);
    // fetch list of users
    Route::get('/userslist', [UserController::class, 'list'])->name('userslist');
    // fetch list of roles
    Route::get('/roleslist', [RoleController::class, 'list'])->name('roleslist');
 });
