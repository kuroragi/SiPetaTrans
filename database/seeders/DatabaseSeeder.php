<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SecondRolePermissionSeeder::class,
            TrayekPermissionSeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@sipeta.com',
            'password' => bcrypt('Zaq123qwerty'),
        ]);

        $admin->assignRole('super-admin');

        $this->call([
            AssetTypeSeeder::class,
            AssetSeeder::class,
        ]);
    }
}
