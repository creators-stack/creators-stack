<?php

use App\Http\Controllers\FileController;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\EditCreator;
use App\Http\Livewire\ListCreators;
use App\Http\Livewire\ListImages;
use App\Http\Livewire\ListVideos;
use App\Http\Livewire\Logs;
use App\Http\Livewire\Settings;
use App\Http\Livewire\ViewCreator;
use App\Http\Livewire\ViewVideo;
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

Route::middleware('auth:web')->group(function () {
    Route::get('/', Dashboard::class)
        ->name('dashboard');

    Route::get('/settings', Settings::class)
        ->name('settings');

    Route::get('/logs', Logs::class)
        ->name('logs');

    Route::prefix('creators')->group(function () {
        Route::get('/', ListCreators::class)
            ->name('creators');

        Route::get('/create', EditCreator::class)
            ->name('creators.create');

        Route::get('/{creator:username}', ViewCreator::class)
            ->name('creators.view');

        Route::get('/{creator:username}/edit', EditCreator::class)
            ->name('creators.edit');
    });

    Route::prefix('pictures')->group(function () {
        Route::get('/', ListImages::class)
            ->name('images');

        Route::get('{file:hash}', [FileController::class, 'serveFile'])
            ->name('image');
    });

    Route::prefix('videos')->group(function () {
        Route::get('/', ListVideos::class)
            ->name('videos');

        Route::get('/{hash}', ViewVideo::class)
            ->name('video');

        Route::get('/stream/{file:hash}', [FileController::class, 'streamVideo'])
            ->name('stream');
    });
});
