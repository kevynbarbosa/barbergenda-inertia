<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('roles-and-permissions', function () {
    return Inertia::render('rbac/RolesAndPermissions');
})->middleware(['auth', 'verified'])->name('roles-and-permissions');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
