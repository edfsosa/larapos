<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'cajero',
            'guard_name' => 'web',
        ]);

        // Asignar super_admin al primer usuario (tu cuenta)
        $admin = User::where('email', 'edfsosa@gmail.com')->first();
        $admin?->assignRole($superAdmin);

        if ($admin) {
            $this->command->info("super_admin asignado a {$admin->email}");
        }
    }
}
