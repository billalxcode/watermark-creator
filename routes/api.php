<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["prefix" => "image", "as" => "api.image."], function () {
    Route::post("fetch", [ImageController::class, "fetch"])->name("fetch");
    Route::post("check", [ImageController::class, "check"])->name("check");
    Route::post("upload", [ImageController::class, "upload"])->name("upload");
});