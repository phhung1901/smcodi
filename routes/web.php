<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // Dump session data để debug
    \Illuminate\Support\Facades\Log::info('Homepage accessed', [
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'auth_check' => auth()->check(),
        'user_id' => auth()->id(),
        'user_email' => auth()->user()?->email,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    dd([
        'session_id' => session()->getId(),
        'session_all' => session()->all(),
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user(),
        'cookies' => request()->cookies->all(),
    ]);

    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
