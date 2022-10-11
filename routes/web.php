<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth42Controller;
use App\Http\Controllers\DashboardController;

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

Route::get('/', [DashboardController::class, 'welcome'])
    ->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/auth/redirect', [Auth42Controller::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/auth/callback', [Auth42Controller::class, 'callback'])
    ->name('auth.callback');

Route::get('/query', [Auth42Controller::class, 'query']);

Route::get('/mytoken', [Auth42Controller::class, 'mytoken']);

// Route::get('/login', function(){
//     $user = App\Models\User::where('login', 'dthalman')->first();
//     Illuminate\Support\Facades\Auth::login($user);
// });

require __DIR__.'/auth.php';
