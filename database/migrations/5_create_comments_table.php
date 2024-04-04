<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment_content');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreignId('event_id')->on('events')->nullable()->constrained();
            $table->foreignId('product_id')->on('products')->nullable()->constrained();
            $table->foreignId('user_id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
