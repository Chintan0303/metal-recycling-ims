<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicProcessing extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function scrap()
    {
        return $this->belongsTo(Scrap::class);
    }

    public function purchaseLineItem()
    {
        return $this->belongsTo(PurchaseLineItem::class);
    }

    public function basicProcessingMaterials()
    {
        return $this->hasMany(BasicProcessingMaterial::class);
    }

    public function advancedProcessings()
    {
        return $this->hasMany(AdvancedProcessing::class);
    }

    public function getUnProcessedQuantityAttribute()
    {
        return $this->qty - $this->processed;
    }

    public function getStatusAttribute()
    {
        return $this->end_date == null ? 'In Progress' : 'Processed';
    }
}
