<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::create([
            "name"=> "admin",
            "email"=> "admin@mail.com",
            "password"=> bcrypt("123456789"),
            "username"=> "admin",
        ]);    
    }
}
