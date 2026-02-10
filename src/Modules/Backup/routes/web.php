<?php

use Illuminate\Support\Facades\Route;
use Nasirkhan\ModuleManager\Modules\Backup\Controllers\BackupController;

/*
|--------------------------------------------------------------------------
| Backup Module Routes
|--------------------------------------------------------------------------
|
| These routes handle the backup management functionality.
| All routes are prefixed with 'admin/backups' and require authentication.
|
*/

Route::middleware(['web', 'auth'])->prefix('admin')->name('backend.')->group(function () {
    /*
     *  Backup Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'backups';
    Route::get("{$module_name}", [BackupController::class, 'index'])->name("{$module_name}.index");
    Route::get("{$module_name}/create", [BackupController::class, 'create'])->name("{$module_name}.create");
    Route::get("{$module_name}/download/{file_name}", [BackupController::class, 'download'])->name("{$module_name}.download");
    Route::get("{$module_name}/delete/{file_name}", [BackupController::class, 'delete'])->name("{$module_name}.delete");
});
