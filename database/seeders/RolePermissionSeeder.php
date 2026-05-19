<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            'view asset types',
            'create asset types',
            'edit asset types',
            'delete asset types',
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $roleAdmin = Role::create(['name' => 'super-admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleOperator = Role::create(['name' => 'operator']);
        $roleOperator->givePermissionTo([
            'view assets',
            'create assets',
            'edit assets',
            'view asset types',
        ]);
        
        $roleViewer = Role::create(['name' => 'viewer']);
        $roleViewer->givePermissionTo([
            'view assets',
            'view asset types',
        ]);
    }
}
