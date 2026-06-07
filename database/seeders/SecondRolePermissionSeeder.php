<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SecondRolePermissionSeeder extends Seeder
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
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view assets monitoring',
            'create assets monitoring',
            'edit assets monitoring',
            'delete assets monitoring',
            'view damage reports',
            'create damage reports',
            'edit damage reports',
            'delete damage reports',
            'view maintenances',
            'create maintenances',
            'edit maintenances',
            'delete maintenances',
            'view reports',
            'print reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $roleAdmin = Role::where('name', 'super-admin')->firstOrFail();
        $roleAdmin->givePermissionTo(Permission::all());

        // Assign to operator (all except delete)
        $roleOperator = Role::where('name', 'operator')->firstOrFail();
        $operatorPermissions = collect($permissions)->reject(function ($name) {
            return str_contains($name, 'delete');
        });
        $roleOperator->givePermissionTo($operatorPermissions->toArray());
        
        // Viewer gets no additional permissions in this seeder
    }
}
