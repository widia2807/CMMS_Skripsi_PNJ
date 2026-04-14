<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', fn() => view('auth.login'));
Route::get('/register', fn() => view('auth.register'));

Route::get('/dashboard-lite', fn() => view('dashboard.lite', [
    'user' => auth()->user()
]));

Route::get('/dashboard-pic', fn() => view('dashboard.dashboard-pic', [
    'user' => auth()->user()
]));

Route::get('/request', fn() => view('request', [
    'user' => auth()->user()
]));

Route::get('/cabang', fn() => view('full_menu.cabang', [
    'user' => auth()->user()
]));

Route::get('/users', fn() => view('full_menu.user_management', [
    'user' => auth()->user()
]));
Route::get('/maintenance', function () {
    return view('maintenance');
});

Route::get('/dashboard-admin', function () {
    return view('dashboard.dashboard_admin_ga');
});

Route::get('/request-list', function () {
    return view('admin_ga.request_list', [
        'user' => auth()->user()
    ]);
});

Route::get('/dashboard-full', function () {
    return view('dashboard.full', [
        'user' => auth()->user()
    ]);
});

