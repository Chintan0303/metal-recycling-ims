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
        Schema::create('advanced_processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scrap_id')->nullable()->references('id')->on('scraps')->cascadeOnDelete();
            $table->foreignId('purchase_line_item_id')->references('id')->on('purchase_line_items')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->cascadeOnDelete();
            $table->foreignId('basic_processing_id')->nullable()->references('id')->on('basic_processings')->cascadeOnDelete();
            $table->decimal('qty',8,3);
            $table->decimal('dust',8,3)->default(0.000);
            $table->decimal('processed',8,3)->default(0.000);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanced_processings');
    }
};
