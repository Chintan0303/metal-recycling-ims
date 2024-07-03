<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicProcessingMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function basicProcessing()
    {
        return $this->belongsTo(BasicProcessing::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
