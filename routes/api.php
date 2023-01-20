<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/trainer','App\Http\Controllers\TrainerController@store');
Route::middleware(['auth:sanctum'])->get('/trainer','App\Http\Controllers\TrainerController@index');

Route::middleware(['auth:sanctum'])->put('/inscription','App\Http\Controllers\InscriptionController@update');
Route::middleware(['auth:sanctum'])->get('/inscription','App\Http\Controllers\InscriptionController@index');

Route::post('login', 'App\Http\Controllers\LoginController@login')->name('login');
Route::middleware(['auth:sanctum'])->post('logout', 'App\Http\Controllers\LoginController@logout')->name('logout');