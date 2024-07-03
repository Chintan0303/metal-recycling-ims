<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrap extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_base' => 'boolean',
        ];
    }

    public function purchaseLineItems()
    {
        return $this->hasMany(PurchaseLineItem::class);
    }

    public function basicProcessings()
    {
        return $this->hasMany(BasicProcessing::class);
    }
}
