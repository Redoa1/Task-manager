<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Route::get('/', function () {
//     return view('welcome');
// })->middleware(['auth'])->name('home');

Route::get('/', [TaskController::class, 'index'])->name('home');

Route::resource('tasks', TaskController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
