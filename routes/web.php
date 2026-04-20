<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', \App\Livewire\Auth\UnifiedLogin::class)->name('login');
