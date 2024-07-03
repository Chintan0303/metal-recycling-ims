<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedProcessing extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scrap()
    {
        return $this->belongsTo(Scrap::class);
    }

    public function purchaseLineItem()
    {
        return $this->belongsTo(PurchaseLineItem::class);
    }

    public function basicProcessing()
    {
        return $this->belongsTo(BasicProcessing::class);
    }

    public function advancedProcessingProducts()
    {
        return $this->hasMany(AdvancedProcessingProduct::class);
    }
    
    public function getUnProcessedQuantityAttribute()
    {
        return $this->qty - $this->processed;
    }

    public function getStatusAttribute()
    {
        return $this->end_date == null ? 'In Progress' : 'Processed';
    }

    public function getTypeAttribute()
    {
        return $this->scrap()->count() ? 'scrap' : 'product';    
    }
}
