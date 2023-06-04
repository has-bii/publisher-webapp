<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\EditorController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\PublisherController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\ChatController;

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
        Route::post('change-photo/{id}', [UserController::class, 'change_photo'])->name('change_photo');
        Route::post('change-role/{id}', [UserController::class, 'update_user'])->name('update_user');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
        Route::get('get-users', [UserController::class, 'fetch_users'])->name('fetch_users');
    });
});

Route::prefix('publisher')->middleware('auth:sanctum')->name('publisher.')->group(function () {
    Route::get('', [PublisherController::class, 'fetch'])->name('fetch');
    Route::post('', [PublisherController::class, 'create'])->name('create');
    Route::post('remove-member', [PublisherController::class, 'remove_member'])->name('remove_member');
    Route::post('add-member', [PublisherController::class, 'add_member'])->name('add_member');
    Route::post('update/{id}', [PublisherController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [PublisherController::class, 'delete'])->name('delete');
});

Route::get('editor', [EditorController::class, 'fetch'])->middleware('auth:sanctum');

Route::prefix('announcement')->middleware('auth:sanctum')->name('announcement')->group(function () {
    Route::get('', [AnnouncementController::class, 'fetch'])->name('fetch');
    Route::get('fetch-announcement', [AnnouncementController::class, 'fetch_publisher'])->name('fetch_publisher');
    Route::post('', [AnnouncementController::class, 'create'])->name('create');
    Route::post('update/{id}', [AnnouncementController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [AnnouncementController::class, 'delete'])->name('delete');
});

Route::prefix('content')->middleware('auth:sanctum')->name('content')->group(function () {
    Route::get('', [ContentController::class, 'fetch'])->name('fetch');
    Route::post('', [ContentController::class, 'create'])->name('create');
    Route::post('update/{id}', [ContentController::class, 'update'])->name('update');
    Route::delete('delete', [ContentController::class, 'delete'])->name('delete');
});

Route::prefix('status')->middleware('auth:sanctum')->name('status')->group(function () {
    Route::get('', [StatusController::class, 'fetch'])->name('fetch');
});

Route::prefix('genre')->middleware('auth:sanctum')->name('genre')->group(function () {
    Route::get('', [GenreController::class, 'fetch'])->name('fetch');
    Route::post('', [GenreController::class, 'create'])->name('create');
    Route::post('update/{id}', [GenreController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [GenreController::class, 'delete'])->name('delete');
});

Route::prefix('type')->middleware('auth:sanctum')->name('type')->group(function () {
    Route::get('', [TypeController::class, 'fetch'])->name('fetch');
    Route::post('', [TypeController::class, 'create'])->name('create');
    Route::post('update/{id}', [TypeController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [TypeController::class, 'delete'])->name('delete');
});

Route::prefix('message')->middleware('auth:sanctum')->name('message')->group(function () {
    Route::get('chat', [ChatController::class, 'fetch'])->name('fetch');
    Route::get('messages', [MessageController::class, 'fetch'])->name('fetch');
    Route::post('send-message', [MessageController::class, 'new_message'])->name('new_message');
    Route::post('new-chat', [MessageController::class, 'new_chat'])->name('new_chat');
});
