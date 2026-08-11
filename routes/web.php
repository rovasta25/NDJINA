<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlerteApiController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/a-propos', AboutController::class)->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:5,10')
        ->name('register.store');
});

Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/alertes/api', [AlerteApiController::class, 'index'])->name('alertes.api');
    Route::get('/alertes', [AlerteController::class, 'index'])->name('alertes.index');
    Route::get('/alertes/creer', [AlerteController::class, 'create'])->name('alertes.create');
    Route::post('/alertes', [AlerteController::class, 'store'])->name('alertes.store');
    Route::get('/alertes/mes', [AlerteController::class, 'mesAlertes'])->name('alertes.mes');
    Route::get('/alertes/{alerte}', [AlerteController::class, 'show'])->name('alertes.show');
    Route::get('/alertes/{alerte}/modifier', [AlerteController::class, 'edit'])->name('alertes.edit');
    Route::put('/alertes/{alerte}', [AlerteController::class, 'update'])->name('alertes.update');
    Route::post('/alertes/{alerte}/statut', [AlerteController::class, 'setStatut'])->name('alertes.statut');
    Route::delete('/alertes/{alerte}', [AlerteController::class, 'destroy'])->name('alertes.destroy');

    Route::post('/alertes/{alerte}/repondre', [ReponseController::class, 'store'])->name('reponses.store');
    Route::post('/reponses/{reponse}/confirmer', [ReponseController::class, 'confirmer'])->name('reponses.confirmer');
    Route::post('/reponses/{reponse}/refuser', [ReponseController::class, 'refuser'])->name('reponses.refuser');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{alerte}/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{alerte}/{user}', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/support', [SupportController::class, 'userIndex'])->name('support.index');
    Route::post('/support', [SupportController::class, 'userStore'])->name('support.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/alertes', [AdminController::class, 'alertes'])->name('admin.alertes');
    Route::put('/alertes/{alerte}', [AdminController::class, 'setStatut'])->name('admin.alertes.statut');
    Route::get('/comptes', [AdminController::class, 'comptes'])->name('admin.comptes');
    Route::put('/comptes/{user}', [AdminController::class, 'setType'])->name('admin.comptes.type');
    Route::delete('/comptes/{user}', [AdminController::class, 'destroyUser'])->name('admin.comptes.destroy');

    Route::get('/support', [SupportController::class, 'adminIndex'])->name('admin.support');
    Route::get('/support/{user}', [SupportController::class, 'adminShow'])->name('admin.support.show');
    Route::post('/support/{user}', [SupportController::class, 'adminStore'])->name('admin.support.store');
    Route::put('/support/{user}/traite', [SupportController::class, 'adminTraite'])->name('admin.support.traite');
});
