<?php

namespace App\Services;

use App\Models\AdminUser;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    public function getAdminUsers(string $search, int $per_page = 30): LengthAwarePaginator
    {
        $query = AdminUser::select('admin_users.*');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
                $query->orWhere('username', 'like', '%'.$search.'%');
                $query->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $paginator = $query->orderBy('id', 'asc')->paginate($per_page);

        if ($paginator->currentPage() > $paginator->lastPage()) {
            request()->merge(['page' => $paginator->lastPage()]);

            return $query->paginate($per_page);
        }

        return $paginator;
    }

    public function storeAdminUser(array $data): AdminUser
    {
        return AdminUser::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateAdminUser(array $data, AdminUser $adminUser): AdminUser
    {
        $adminUser->update($data);

        return $adminUser->fresh();
    }

    public function updatePasswordAdminUser(string $password, AdminUser $adminUser): void
    {
        $adminUser->update([
            'password' => Hash::make($password),
        ]);
    }
}
