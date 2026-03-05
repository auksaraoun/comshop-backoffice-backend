<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::firstOrcreate(
            [
                'email' => 'admin@mail.com',
                'username' => 'admin',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('123456789'),
                'username' => 'admin',
            ]
        );

        AdminUser::factory(1000)->create();
    }
}
