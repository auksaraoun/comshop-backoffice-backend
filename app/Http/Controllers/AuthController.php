<?php

namespace App\Http\Controllers;

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

    public function fetchAuth(){
        $adminUser = auth('web')->user();
        return $this->response->success(
            new AdminUserResource($adminUser),
            'Fetch Admin user success',
        );
    }

}
