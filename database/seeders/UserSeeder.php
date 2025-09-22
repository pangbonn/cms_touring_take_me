<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $reportRole = Role::where('name', 'report')->first();

        // Create superadmin user
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        // Create report user
        User::firstOrCreate(
            ['email' => 'report@example.com'],
            [
                'name' => 'Report User',
                'first_name' => 'Report',
                'last_name' => 'User',
                'email' => 'report@example.com',
                'password' => Hash::make('password123'),
                'role_id' => $reportRole->id,
                'is_active' => true,
                'must_change_password' => true, // Force password change for demo
            ]
        );
    }
}
