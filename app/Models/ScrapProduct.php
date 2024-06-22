<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the line items for the scrap product.
     */
    public function lineItems()
    {
        return $this->hasMany(PurchaseLineItem::class);
    }

    public function basicProcessings()
    {
        return $this->hasMany(BasicProcessing::class);
    }
}
