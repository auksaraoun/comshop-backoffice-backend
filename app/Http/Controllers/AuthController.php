<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminUserResource;
use App\Utils\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private ApiResponse $response
    ){}

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'ชื่อหรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        $admin = Auth::user();
        $admin->createToken('admin-token', ['role:admin']);

        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',
        ]);
    }

    public function fetchAuth()
    {
        $adminUser = auth('web')->user();
        return $this->response->success(
            new AdminUserResource($adminUser),
            'Fetch Admin user success',
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
        
        return $this->response->success(
            null,
            'Log Out success',
        );
    }

}
