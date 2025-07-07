<?php

use App\Livewire\Admin\UsersList;
use App\Livewire\TransactionTable;
use App\Livewire\AnalyticsDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/transactions', TransactionTable::class)->name('transactions');
    Route::get('/analytics', AnalyticsDashboard::class)->name('analytics');

    Route::middleware([AdminMiddleware::class])
        ->group(function () {
            Route::get('/admin/users', UsersList::class)->name('admin.users');
        });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
