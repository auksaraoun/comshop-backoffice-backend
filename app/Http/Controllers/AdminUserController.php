<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUser\IndexAdminRequest;
use App\Http\Requests\AdminUser\StoreAdminUserRequest;
use App\Http\Requests\AdminUser\UpdateAdminUserRequest;
use App\Http\Requests\AdminUser\UpdatePasswordAdminUserRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\AdminUser;
use App\Services\AdminUserService;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminUserController extends Controller
{
    public function __construct(
        private ApiResponse $response,
        private AdminUserService $adminUserService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexAdminRequest $request): JsonResponse
    {
        $validate = $request->validated();
        $search = $validate['search'] ?? '';
        $per_page = $validate['per_page'] ?? 30;
        $adminUsers = $this->adminUserService->getAdminUsers($search, $per_page);

        return $this->response->success(
            AdminUserResource::collection($adminUsers),
            'Fetch Admin Users success',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $adminUser = $this->adminUserService->storeAdminUser($data);

        return $this->response->success(
            new AdminUserResource($adminUser),
            'Store Admin User success',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminUser $adminUser): JsonResponse
    {
        return $this->response->success(
            new AdminUserResource($adminUser),
            'Fetch Admin user success',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser): JsonResponse
    {
        $data = $request->validated();
        $adminUser = $this->adminUserService->updateAdminUser($data, $adminUser);

        return $this->response->success(
            new AdminUserResource($adminUser),
            'Update admin user success'
        );
    }

    public function updatePassword(UpdatePasswordAdminUserRequest $request, AdminUser $adminUser): JsonResponse
    {
        $data = $request->validated();
        $password = $data['password'];
        $this->adminUserService->updatePasswordAdminUser($password, $adminUser);

        return $this->response->success(
            null,
            "Update password's admin user success",
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminUser $adminUser): JsonResponse
    {
        $adminUser->delete();

        return $this->response->success(
            null,
            'Delete admin user success',
            200
        );
    }
}
