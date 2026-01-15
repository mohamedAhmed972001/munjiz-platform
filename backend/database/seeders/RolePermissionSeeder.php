<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define permissions list (adjust to needs)
        $permissions = [
            'create project',
            'edit own project',
            'delete own project',
            'accept bid',
            'bid on project',
            'manage profile',
            'manage categories',
            'manage users',
            'view analytics',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $client = Role::firstOrCreate(['name' => 'client']);
        $freelancer = Role::firstOrCreate(['name' => 'freelancer']);

        // Assign permissions
        // admin gets all
        $admin->syncPermissions(Permission::all());

        // client: project creator rights + accept bid
        $client->syncPermissions([
            'create project',
            'edit own project',
            'delete own project',
            'accept bid',
            'manage profile',
        ]);

        // freelancer: can bid and manage profile
        $freelancer->syncPermissions([
            'bid on project',
            'manage profile',
        ]);
    }
}
