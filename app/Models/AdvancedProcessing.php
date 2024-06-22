<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedProcessing extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 'qty', 'processed', 'start_date', 'end_date'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function products()
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
}
