<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AuthBaseModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Admin::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@dev.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@dev.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
            'role_id' => 1,
        ]);
        $superadmin->assignRole($superadmin->role->name);
        $admin = Admin::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@dev.com',
            'email_verified_at' => now(),
            'password' => 'admin@dev.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
            'role_id' => 2,
        ]);
        $admin->assignRole($admin->role->name);
        $test = Admin::create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'testadmin@dev.com',
            'password' => 'testadmin@dev.com',
            'role_id' => 2,
        ]);
        $test->assignRole($test->role->name);
    }
}
