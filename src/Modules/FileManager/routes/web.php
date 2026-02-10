<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File Manager Module Routes
|--------------------------------------------------------------------------
|
| These routes handle the Laravel File Manager functionality.
| All routes require authentication and backend access permission.
|
*/

Route::middleware(['web', 'auth', 'can:view_backend'])->prefix('laravel-filemanager')->group(function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
