<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'ref', 'vendor_id'];

    /**
     * Get the vendor that owns the purchase.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the line items for the purchase.
     */
    public function lineItems()
    {
        return $this->hasMany(PurchaseLineItem::class);
    }
}
