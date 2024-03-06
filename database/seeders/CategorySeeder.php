<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CREATE CATEGORIES BOOK AND OTHER
        $category = [
            ['category_name' => 'Livre'],
            ['category_name' => 'Autre'],
        ];

        foreach ($category as $categoryData) {
            Category::create($categoryData);
        }
    }
}
