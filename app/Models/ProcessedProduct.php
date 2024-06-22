<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the sale line items for the processed product.
     */
    public function saleLineItems()
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public function advancedProcessingProducts()
    {
        return $this->hasMany(AdvancedProcessingProduct::class);
    }
}
