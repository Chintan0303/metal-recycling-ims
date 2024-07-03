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
        Schema::create('basic_processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scrap_id')->constrained('scraps')->cascadeOnDelete();
            $table->decimal('qty',8,3);
            $table->decimal('dust',8,3)->default(0.000);
            $table->decimal('processed',8,3)->default(0.000);
            $table->foreignId('purchase_line_item_id')->constrained('purchase_line_items')->cascadeOnDelete();
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
        Schema::dropIfExists('basic_processings');
    }
};
