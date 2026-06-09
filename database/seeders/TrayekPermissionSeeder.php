<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TrayekPermissionSeeder extends Seeder
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
            'view trayeks',
            'create trayeks',
            'edit trayeks',
            'delete trayeks',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $roleAdmin = Role::where('name', 'super-admin')->first();
        if ($roleAdmin) {
            $roleAdmin->givePermissionTo($permissions);
        }

        // Assign to operator (all except delete)
        $roleOperator = Role::where('name', 'operator')->first();
        if ($roleOperator) {
            $operatorPermissions = collect($permissions)->reject(function ($name) {
                return str_contains($name, 'delete');
            });
            $roleOperator->givePermissionTo($operatorPermissions->toArray());
        }

        // Viewer gets view permissions
        $roleViewer = Role::where('name', 'viewer')->first();
        if ($roleViewer) {
            $roleViewer->givePermissionTo('view trayeks');
        }
    }
}
