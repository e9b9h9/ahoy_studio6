<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('podbud-dashboard', function () {
    return Inertia::render('PodbudDashboard');
})->middleware(['auth', 'verified'])->name('podbud-dashboard');

Route::get('codemate-dashboard', [App\Http\CodemateSettings\FolderFileController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('codemate-dashboard');

Route::get('codemate-github', [App\Http\Codemate\CodemateGitHubController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('codemate-github');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/codemate.php';
require __DIR__.'/codemate-settings.php';
