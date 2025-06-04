<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Usuario básico de prueba
         $user = User::firstOrCreate(
            ['email' => 'correo@correo.com'],
            [
                'name'              => 'User Test',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');
    }
}
