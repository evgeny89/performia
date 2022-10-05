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

Route::get("/apartments", [\App\Http\Controllers\ApartmentController::class, "all"]);

Route::post("/upload", [\App\Http\Controllers\FileController::class, "import"]);
Route::post("/search", [\App\Http\Controllers\ApartmentController::class, "search"]);
