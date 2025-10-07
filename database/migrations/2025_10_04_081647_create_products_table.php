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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('cost_price', 15, 2)->nullable()->comment('Harga beli');
            $table->string('sku')->unique()->nullable()->comment('Stock Keeping Unit');
            $table->string('barcode')->unique()->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0)->comment('Stok minimum untuk alert');
            $table->string('category');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->json('attributes')->nullable()->comment('Additional attributes like size, color, etc');
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performa
            $table->index('name');
            $table->index('category');
            $table->index('sku');
            $table->index('barcode');
            $table->index('is_active');
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
