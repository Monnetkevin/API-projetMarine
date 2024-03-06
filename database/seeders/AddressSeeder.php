<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CREATE ADDRESS FOR THE ADMIN
        Address::create([
            'address_name' => 'résidence',
            'address' => '17 avenue de paderborn',
            'postal_code' => '72000',
            'city'=> 'LE MANS',
            'country' => 'France',
            'user_id' => 1,
        ]);
    }
}
