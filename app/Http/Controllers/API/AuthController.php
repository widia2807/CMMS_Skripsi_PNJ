<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Company;
use App\Models\Academic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
{

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'system_type' => 'required|in:full,lite',
        'company_name' => 'required'
    ]);

    $company = Company::create([
        'name' => $request->company_name
    ]);
    $role = $request->system_type == 'full' ? 'super_admin' : 'admin';

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),

        'mode' => 'company',
        'system_type' => $request->system_type,
        'company_id' => $company->id,

        'role' => $role,
        'status' => 'active'
    ]);

    return response()->json([
        'message' => 'Register berhasil',
        'user' => $user
    ]);
}

    public function login(Request $request)
{

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }
    if ($user->status !== 'active') {
        return response()->json([
            'message' => 'User tidak aktif'
        ], 403);
    }

    if ($user->branch && $user->branch->status === 'inactive') {
        return response()->json([
            'message' => 'Cabang tidak aktif'
        ], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'user' => $user,
        'token' => $token,
        'system_type' => $user->system_type,
        'role' => $user->role
    ]);
}
}