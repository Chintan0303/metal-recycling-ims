<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedProcessingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'advanced_processing_id', 'processed_product_id', 'qty', 'date'
    ];

    public function advancedProcessing()
    {
        return $this->belongsTo(AdvancedProcessing::class);
    }

    public function processedProduct()
    {
        return $this->belongsTo(ProcessedProduct::class);
    }
}
