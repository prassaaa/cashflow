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
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create super admin user
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cashflow.test',
        ]);
        $superAdmin->assignRole('super_admin');

        // Create test users for each role
        $roles = ['hrd', 'marketing', 'purchasing', 'accounting', 'ppic'];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'name' => ucfirst($role) . ' User',
                'email' => $role . '@cashflow.test',
            ]);
            $user->assignRole($role);
        }

        // // Seed demo data
        $this->call(DemoDataSeeder::class);
    }
}
