<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

// Rute untuk halaman backend
Route::get('backend/home', [HomeController::class, 'homeBackend'])->name('backend.home');

// Rute untuk login
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login'); // Rute untuk menampilkan halaman login
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.authenticate'); // Rute untuk proses login
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout'); // Rute untuk logout
