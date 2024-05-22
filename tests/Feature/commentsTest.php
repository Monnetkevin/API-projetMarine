<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class commentsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_new_comment_created(): void
    {
        $response = $this->post('api/comments', [
            'comment_content' => 'test',
            'product_id' => 1,
            'user_id' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function test_comments_listed_successfully()
    {
        $this->json('GET', 'api/comments', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
