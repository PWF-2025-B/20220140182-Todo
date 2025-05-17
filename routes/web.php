<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Halaman utama
Route::get('/', function () {
    Log::info('Welcome page loaded');
    return view('welcome');
});

// Halaman dashboard (hanya untuk yang sudah login dan verifikasi email)
Route::get('/dashboard', function () {
    Log::info('Dashboard page loaded');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua route yang butuh otentikasi user
Route::middleware('auth')->group(function () {
    // Profil user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Todo routes
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
    Route::get('/todo/create', [TodoController::class, 'create'])->name('todo.create');
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
    Route::get('/todo/{todo}/edit', [TodoController::class, 'edit'])->name('todo.edit');
    Route::patch('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');
    Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
    Route::patch('/todo/{todo}/incomplete', [TodoController::class, 'incomplete'])->name('todo.uncomplete');
    Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
    Route::delete('/todo', [TodoController::class, 'destroyCompleted'])->name('todo.deleteallcompleted');
    
    // Manajemen user (jika admin)
    // Route::get('/user', [UserController::class, 'index'])->name('user.index');
    // Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
    // Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');
    // Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('user', UserController::class)->except(['show']);
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
    Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::resource('category', CategoryController::class);
});

Route::get('/pzn', function (){
    return "Hello Programmer Zaman Now";
});

require __DIR__.'/auth.php';