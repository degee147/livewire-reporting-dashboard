<?php

use App\Livewire\Admin\UsersList;
use App\Livewire\TransactionTable;
use App\Livewire\AnalyticsDashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/transactions', TransactionTable::class)->name('transactions');
    Route::get('/analytics', AnalyticsDashboard::class)->name('analytics');



    Route::get('/impersonate/stop', function () {
        if (Session::has('impersonate')) {
            auth()->loginUsingId(Session::pull('impersonate'));
        }

        return redirect()->route('dashboard')->with('message', 'You have returned to your admin account.');
    })->name('impersonate.stop');


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
