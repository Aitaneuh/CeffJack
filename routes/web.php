<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\BlackjackController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Auth & profil
Route::middleware('auth')->group(function () {
    // Page de profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/reset-progress', [BlackjackController::class, 'resetProgress'])->name('profile.reset-progress');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes protégées par 'auth'
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Blackjack
    Route::get('/blackjack', [BlackjackController::class, 'index'])->name('blackjack');
    Route::post('/blackjack/hit', [BlackjackController::class, 'hit'])->name('blackjack.hit');
    Route::post('/blackjack/stand', [BlackjackController::class, 'stand'])->name('blackjack.stand');
    Route::post('/blackjack/reset', [BlackjackController::class, 'reset'])->name('blackjack.reset');

    // Statistiques & historique
    Route::get('/stats', [StatsController::class, 'index'])->name('stats');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/history', [BlackjackController::class, 'history'])->name('history');

    // Bonus (important)
    Route::post('/bonus/claim', [BonusController::class, 'claim'])->name('bonus.claim');

    // Règles et raccourcis
    Route::get('/dashboard/profile', fn() => view('profile'))->name('profile');
    Route::get('/rules', fn() => view('rules'))->name('rules');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::patch('/admin/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.toggleAdmin');
    Route::patch('/admin/users/{user}/reset-balance', [AdminController::class, 'resetBalance'])->name('admin.resetBalance');
    Route::patch('/admin/users/{user}/update-balance', [AdminController::class, 'updateBalance'])->name('admin.updateBalance');

    Route::get('/adminstats', [AdminStatsController::class, 'index'])->name('adminstats');
});

// Authentification Laravel Breeze/Jetstream
require __DIR__ . '/auth.php';
