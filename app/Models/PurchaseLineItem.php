<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['qty', 'scrap_id', 'purchase_id'];

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
    public function scrap()
    {
        return $this->belongsTo(Scrap::class);
    }

    public function basicProcessings()
    {
        return $this->hasMany(BasicProcessing::class);
    }

    public function advancedProcessings()
    {
        return $this->hasMany(AdvancedProcessing::class);
    }

    public function getIsBasicProcessedAttribute()
    {
        if ($this->basicProcessings()->count()) {
           return $this->basicProcessings()->first()->end_date !== null ? 'Processed' : 'In Progress';     
        }
        return 'Not Applicable';
    }

    public function getIsAdvProcessedAttribute()
    {
        if ($this->advancedProcessings()->count()) {
           return $this->advancedProcessings()->first()->end_date !== null ? 'Processed' : 'In Progress';     
        }
        return 'Not Applicable';
    }
}
