<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function update(Request $request)
{
    $user = auth()->user();

    $company = Company::find($user->company_id);

    $request->validate([
        'name' => 'required'
    ]);

    $company->update([
        'name' => $request->name
    ]);

    return response()->json([
        'message' => 'Company berhasil diupdate',
        'company' => $company
    ]);
}
}
