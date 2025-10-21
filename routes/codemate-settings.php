<?php

use App\Http\CodemateSettings\FolderFileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('codemate/folder-files', [FolderFileController::class, 'store'])->name('codemate.folder-files.store');
    Route::put('codemate/folder-files/{folderFile}', [FolderFileController::class, 'update'])->name('codemate.folder-files.update');
});