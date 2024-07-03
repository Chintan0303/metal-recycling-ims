<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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

    public function basicProcessingMaterials()
    {
        return $this->hasMany(BasicProcessingMaterial::class);
    }

    public function advancedProcessings()
    {
        return $this->hasMany(AdvancedProcessing::class);
    }

    public function advancedProcessingProducts()
    {
        return $this->hasMany(AdvancedProcessingProduct::class);
    }

    public function getInProcessAttribute()
    {
        if ($this->id !== 1) {
            return 'Not Applicable';
        }
        $total = $this->advancedProcessings()->whereNull('end_date')->sum('qty') ;
        $processed = $this->advancedProcessings()->whereNull('end_date')->sum('processed') ; 
        
        return $total - $processed;
    }
}
