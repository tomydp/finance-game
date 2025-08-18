<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user  = Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);

        // Permisos base
        $perms = [
            'courses.view', 'courses.manage',
            'lessons.view', 'lessons.manage',
            'exercises.view', 'exercises.manage',
            'results.view', 'results.manage',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Admin tiene todo
        $admin->syncPermissions(Permission::all());
        // User: sólo ver
        $user->syncPermissions(Permission::whereIn('name', [
            'courses.view', 'lessons.view', 'exercises.view', 'results.view',
        ])->get());
    }
}
