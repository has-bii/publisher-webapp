<?php

use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\EditorController;
use App\Http\Controllers\API\PublisherController;
use App\Http\Controllers\API\UserController;
use App\Models\Announcement;
use App\Models\Publisher;
use Database\Seeders\EditorPublisher;
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

Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::post('update', [UserController::class, 'update'])->name('update');
        Route::get('fetch', [UserController::class, 'fetch'])->name('fetch');
    });
});

Route::prefix('publisher')->middleware('auth:sanctum')->name('publisher.')->group(function () {
    Route::get('', [PublisherController::class, 'fetch'])->name('fetch');
    Route::post('', [PublisherController::class, 'create'])->name('create');
    Route::post('update/{id}', [PublisherController::class, 'update'])->name('update');
});

Route::get('editor', [EditorController::class, 'fetch'])->middleware('auth:sanctum');

Route::prefix('announcement')->middleware('auth:sanctum')->name('announcement')->group(function () {
    Route::get('', [AnnouncementController::class, 'fetch'])->name('fetch');
    Route::post('', [AnnouncementController::class, 'create'])->name('create');
    Route::delete('', [AnnouncementController::class, 'delete'])->name('delete');
});

Route::prefix('content')->middleware('auth:sanctum')->name('content')->group(function () {
    Route::get('', [ContentController::class, 'fetch'])->name('fetch');
    Route::post('', [ContentController::class, 'create'])->name('create');
    Route::post('update/{id}', [ContentController::class, 'update'])->name('update');
});
