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
        Schema::create('products_shop_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('product_quantity')->default(1);
            $table->foreignId('product_id')->on('products');
            $table->foreignId('shopSession_id')->on('shop_sessions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_shop_sessions');
    }
};
