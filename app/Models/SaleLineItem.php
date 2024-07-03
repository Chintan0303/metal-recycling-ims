<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleLineItem extends Model
{
    use HasFactory;

    protected $guarded = [];

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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
