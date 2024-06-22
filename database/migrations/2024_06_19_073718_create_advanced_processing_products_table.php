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
        Schema::create('advanced_processing_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advanced_processing_id')->constrained('advanced_processings')->cascadeOnDelete();
            $table->foreignId('processed_product_id')->constrained('processed_products')->cascadeOnDelete();
            $table->decimal('qty',8,3);
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanced_processing_products');
    }
};
