<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->uuid('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Denormalized untuk performance
            $table->decimal('product_price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->json('product_data')->nullable(); // Backup data produk saat transaksi
            $table->timestamps();

            // Indexes untuk performa query
            $table->index('transaction_id');
            $table->index('product_id');
            $table->index('product_name');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};