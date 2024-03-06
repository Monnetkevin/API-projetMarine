<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ADMIN
        User::create([
            'first_name' => 'MONNET',
            'last_name' => 'Kevin',
            'email' => 'monnet.kevin@hotmail.fr',
            'password' => bcrypt('azerty'),
            'role_id' => 2,
            'phone_number' => '0630206332',
            'image_id' => 1,
        ]);

        // USER
        User::create([
            'first_name' => 'MATISSE',
            'last_name' => 'Marine',
            'email' => 'matisse.marine@hotmail.fr',
            'password' => bcrypt('azerty'),
            'role_id' => 1,
            'phone_number' => '0606060606',
            'image_id' => 1,
        ]);
    }
}
