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
        Schema::create('sale_line_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('qty',8,3);
            $table->foreignId('processed_product_id')->nullable()->references('id')->on('processed_products')->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->references('id')->on('materials')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_line_items');
    }
};