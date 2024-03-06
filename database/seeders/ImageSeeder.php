<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CREATE AVATAR DEFAULT FOR THE NEW USER
        Image::create([
            'image_name' => 'default_avatar.jpg',
        ]);
    }
}
