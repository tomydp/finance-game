<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $user = User::where('email', 'alumno@demo.com')->first();
        if ($user) {
            $user->assignRole('user');
        }
    }
}
