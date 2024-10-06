<?php

use App\Livewire\Auth\Users;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');


Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('dashboard', 'dashboard')
            ->name('dashboard');

        Route::view('profile', 'profile')
            ->name('profile');

        Route::get('users', Users::class)->name('users');
        Route::get('appointments', App\Livewire\Appointments\Index::class)->name('appointments.index');
        Route::get('appointments/slips', App\Livewire\Appointments\Slips::class)->name('appointments.slips');
    });

require __DIR__ . '/auth.php';
