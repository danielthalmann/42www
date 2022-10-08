<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth42Controller;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/auth/redirect', [Auth42Controller::class, 'redirect']);

Route::get('/auth/callback', [Auth42Controller::class, 'callback']);

Route::get('/query', [Auth42Controller::class, 'query']);

Route::get('/mytoken', [Auth42Controller::class, 'mytoken']);


require __DIR__.'/auth.php';
