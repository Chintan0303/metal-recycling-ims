<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicProcessingMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_processing_id', 'material_id', 'qty', 'date'
    ];

    public function basicProcessing()
    {
        return $this->belongsTo(BasicProcessing::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
