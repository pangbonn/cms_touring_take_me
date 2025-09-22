<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'ผู้ดูแลระบบสูงสุด',
                'description' => 'มีสิทธิ์เข้าถึงทุกฟีเจอร์และจัดการผู้ใช้ทั้งหมด',
            ],
            [
                'name' => 'admin',
                'display_name' => 'ผู้ดูแลระบบ',
                'description' => 'มีสิทธิ์เข้าถึงฟีเจอร์ส่วนใหญ่ ยกเว้นการจัดการผู้ใช้',
            ],
            [
                'name' => 'report',
                'display_name' => 'เจ้าหน้าที่รายงาน',
                'description' => 'มีสิทธิ์เข้าถึงเฉพาะการดูรายงานและข้อมูลเท่านั้น',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
