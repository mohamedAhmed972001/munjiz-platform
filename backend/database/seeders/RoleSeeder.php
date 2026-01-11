<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأدوار الأساسية للمنصة
        // بنستخدم firstOrCreate عشان لو عملت Seed تاني ميتكرروش ويضرب Error
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'freelancer']);
        Role::firstOrCreate(['name' => 'client']);
    }
}