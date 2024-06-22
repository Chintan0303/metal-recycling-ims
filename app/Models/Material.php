<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the sale line items for the material.
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
}
