<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //FIRST PRODUCT

        Product::create([
            'product_name' => 'la vie d\'une audacieuse',
            'product_content' => 'Rosa est la cheffe d\'un cartel de la drogue à Véra Cruz. Adriana est agent de police. L\'une est cruelle, dénuée d\'âme et sauvage, l\'autre est bienveillante, attentionnée et douce. Alors qu\'elles n\'ont absolument rien en commun, elle vont, petit à petit, intégrer chacune le monde de l\'autre et réaliser qu\'elles se ressemblent peut-être plus qu\'elles veulent le croire.',
            'price' => 19,
            'quantity' => 10,
            // 'user_id' => 1,
            'category_id' => 1,
        ]);
    }
}
