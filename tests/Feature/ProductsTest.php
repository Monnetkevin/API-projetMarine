<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_new_products_created(): void
    {
        $response = $this->post('api/products', [
            'product_name' => 'la vie d\'une audacieuse',
            'product_content' => 'test',
            'price' => 19,
            'quantity' => 10,
            'category_id' => 1,
        ]);

        $response->assertStatus(200);
    }
    public function test_products_listed_successfully()
    {
        $this->json('GET', 'api/products', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
