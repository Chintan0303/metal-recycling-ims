<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicProcessing extends Model
{
    use HasFactory;

    protected $fillable = [
        'scrap_product_id', 'qty', 'processed', 'start_date', 'end_date'
    ];

    public function scrapProduct()
    {
        return $this->belongsTo(ScrapProduct::class);
    }

    public function materials()
    {
        return $this->hasMany(BasicProcessingMaterial::class);
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
