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

Route::get('/', function () {
    return redirect()->route('index');
});

Route::get('/app',  [\App\Http\Controllers\AppController::class, 'index'])->name('index');
Route::get('/app/search', [\App\Http\Controllers\AppController::class, 'searchRoute'])->name('searchRoute');


Route::get('/testing/migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
    echo \Illuminate\Support\Facades\Artisan::output();
});

Route::get('/testing/seed', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed --class=DatabaseSeeder');
    echo \Illuminate\Support\Facades\Artisan::output();
});
