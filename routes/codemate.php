<?php

use App\Http\Codemate\CodemateController;
use App\Http\Codemate\AllFolderFilesWatchedHelper;
use App\Http\Codemate\MountFilesHelper;
use App\Http\Codemate\Requests\FolderFileRequests;
use App\Http\Codemate\Codelines\InitializeFile\InitializeFileService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('codemate', [CodemateController::class, 'index'])->name('codemate');
    Route::get('codemate/watched-files', [AllFolderFilesWatchedHelper::class, 'getWatchedFiles'])->name('codemate.watched-files');
    Route::post('codemate/mount', [MountFilesHelper::class, 'mount'])->name('codemate.mount');
    Route::get('codemate/{id}/request-children', [FolderFileRequests::class, 'requestChildren'])->name('codemate.request-children');
    Route::post('codemate/process-file/init-file', function (Illuminate\Http\Request $request) {
        $service = new InitializeFileService();
        $service->initializeFile($request->input('file_id'));
        return response()->json([]);
    })->name('codemate.init-file');
    
    Route::get('codemate/testing', function () {
        return Inertia::render('codemate/TestingPage');
    })->name('codemate.testing');
});