<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // FIRST COMMENT FOR BOOK
        Comment::create([
            'comment_content' => 'Super livre je recommande à 100%',
            'product_id' => 1,
            'user_id' => 2,
        ]);

        // FIRST COMMENT FOR EVENT
        Comment::create([
            'comment_content' => 'Je vais venir vous voir avec plaisir',
            'event_id' => 1,
            'user_id' => 2,
        ]);
    }
}
