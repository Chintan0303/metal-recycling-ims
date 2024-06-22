<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['qty', 'processed_product_id', 'material_id', 'sale_id'];

    /**
     * Get the sale that owns the line item.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the processed product that the line item refers to.
     */
    public function processedProduct()
    {
        return $this->belongsTo(ProcessedProduct::class);
    }

    /**
     * Get the material that the line item refers to.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
