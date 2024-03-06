<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ROLE USER, ID = 1
        Role::create([
            'role_name' => 'user'
        ]);

        // ROLE ADMIN, ID = 2
        Role::create([
            'role_name' => 'admin'
        ]);
    }
}
