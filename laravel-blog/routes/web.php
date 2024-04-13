<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\EntradaController;
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

Route::get('/', [EntradaController::class, 'index'])
    ->name('home');

Route::resource('entradas', EntradaController::class);
Route::resource('comentarios', ComentarioController::class);
