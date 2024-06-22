<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['qty', 'scrap_product_id', 'purchase_id'];

    /**
     * Get the purchase that owns the line item.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the scrap product that the line item refers to.
     */
    public function scrapProduct()
    {
        return $this->belongsTo(ScrapProduct::class);
    }
}
