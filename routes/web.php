<?php

use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SolicitationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::resource('permissions', PermissionController::class);
    Route::get('solicitations', [SolicitationController::class, 'index'])->name('solicitations.index');
    Route::get('solicitations/create', [SolicitationController::class, 'create'])->name('solicitations.create');
    Route::post('solicitations', [SolicitationController::class, 'store'])->name('solicitations.store');
    Route::get('solicitations/{solicitation}', [SolicitationController::class, 'show'])->name('solicitations.show');
    Route::get('base-ativa', [PeopleController::class, 'index'])->name('people.index');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
