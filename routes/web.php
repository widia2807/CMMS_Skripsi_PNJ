<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard-full', function () {
    return view('dashboard.full');
});

Route::get('/dashboard-lite', function () {
    return view('dashboard.lite');
});
Route::get('/cabang', function () {
    return view('full_menu.cabang');
});
Route::get('/users', function () {
    return view('full_menu.user_management');
});

Route::get('/dashboard-pic', function () {
    return view('dashboard.dashboard-pic');
});

Route::get('/request', function () {
    return view('request');
});
//Route::get('/login', function () {
//    return response()->json([
 //       'message' => 'Silakan login'
   // ], 401);
//})->name('login');