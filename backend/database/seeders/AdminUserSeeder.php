<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. إنشاء المستخدم باستخدام الموديل (عشان الـ Hash والـ Roles يشتغلوا)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@munjiz.com',
            'password' => Hash::make('password123'), // الطريقة الأصح للتشفير
            'is_active' => true,
        ]);

        // 2. تعيين الرول له عن طريق الباكدج
        // السطر ده هو اللي هيخليه Admin حقيقي في جداول Spatie
        $admin->assignRole('admin');
    }
}