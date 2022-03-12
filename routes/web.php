<?php

use App\Http\Controllers\ApiController;
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

Route::get('/api', [ApiController::class, 'Index']);
Route::get('/api/generatequotes/{num}', [ApiController::class, 'GenerateQuotes']);
Route::get('/api/newgame/{num}', [ApiController::class, 'newGame']);
