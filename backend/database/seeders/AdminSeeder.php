<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = env('MUNJIZ_ADMIN_EMAIL', 'admin@munjiz.com');
        $password = env('MUNJIZ_ADMIN_PASSWORD', 'password');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        // assign spatie role
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
