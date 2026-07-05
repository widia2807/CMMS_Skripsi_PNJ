<?php

use Illuminate\Support\Facades\Route;
use App\Services\ResendMailer;

Route::get('/login', fn() => view('auth.login'));
Route::get('/register', fn() => view('auth.register'));

Route::get('/dashboard-pic', fn() => view('dashboard.dashboard-pic', [
    'user' => auth()->user()
]));

Route::get('/request', fn() => view('request', [
    'user' => auth()->user()
]));

Route::get('/cabang', fn() => view('full_menu.cabang', [
    'user' => auth()->user()
]));
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/users', fn() => view('full_menu.user_management', [
    'user' => auth()->user()
]));
Route::get('/maintenance', function () {
    return view('admin_ga.maintenance');
});

Route::get('/tukang/pekerjaan', function () {
    return view('tukang.pekerjaan');
});

Route::get('/assets', function () {
    return view('admin_ga.assets');
});
 
Route::get('/status', fn() => view('pic-status', [
    'user' => auth()->user()
]));

Route::get('/peminjaman', function () {
    return view('pic_peminjaman', [
        'user' => auth()->user()
    ]);
});

Route::get('/admin/peminjaman', function () {
    return view('admin_ga.peminjaman_alat', [
        'user' => auth()->user()
    ]);
});
Route::get('/spk', function () {
    return view('admin_ga.spk_list');
});

Route::get('/work-order/{type}/{id}', function () {
    return view('admin_ga.work_order');
});

Route::get('/borrow-tools', function () {
    return redirect('/peminjaman');
});
//dashboard 
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

Route::get('/dashboard-technician', function () {
    return view('dashboard.dashboard_technician', [
        'user' => auth()->user()
    ]);
});
Route::get('/dashboard-management', function () {
    return view('dashboard.dashboard_management');
});

Route::get('/reports', function () {
    return view('management.laporan');
});

Route::get('/debug-resend-key', function () {
    $key = config('services.resend.key');
    return [
        'ada_key' => $key ? true : false,
        'panjang' => $key ? strlen($key) : 0,
        'awalan'  => $key ? substr($key, 0, 6) : null,
        'akhiran' => $key ? substr($key, -4) : null,
    ];
});

Route::get('/dashboard-lite', fn() => view('dashboard.lite', [
    'user' => auth()->user()
]));
