<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $admin = Auth::user();
        $admin->createToken('admin-token', ['role:admin']);

        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',
        ]);
    }
}
