<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClassController::class, 'indexe'])
    ->name('class.indexe');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')
        ->name('dashboard');
});

Route::get('/groupes', [StudentController::class, 'index'])
    ->name('groupes');

Route::post('/students', [StudentController::class, 'store']);

require __DIR__.'/settings.php';