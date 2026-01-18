<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'create projects',
            'edit projects',
            'delete projects',
            'view projects',
            'bid on projects',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $client = Role::firstOrCreate(['name' => 'client']);
        $freelancer = Role::firstOrCreate(['name' => 'freelancer']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());

        $client->givePermissionTo([
            'create projects',
            'edit projects',
            'delete projects',
            'view projects',
        ]);

        $freelancer->givePermissionTo([
            'view projects',
            'bid on projects',
        ]);
    }
}
