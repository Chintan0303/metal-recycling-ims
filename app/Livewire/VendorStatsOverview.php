<?php

namespace App\Livewire;

use App\Models\PurchaseLineItem;
use App\Models\Vendor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class VendorStatsOverview extends BaseWidget
{
    public Vendor $vendor;

    public function mount($vendor)
    {
        $this->vendor = $vendor;
    }
    
    protected function getStats(): array
    {
        // $purchase_line_items = PurchaseLineItem::whereHas('purchase', function ($query) {
        //     $query->where('vendor_id', $this->vendor->id);
        // })->get();
        $al = 0 ;
        $cu = 0;
        $iron = 0;
        $ingot = 0;
        $kitty = 0;
        $dust = 0;
        foreach ($this->vendor->purchases as $purchase) {
            foreach ($purchase->lineItems as $line_item) {
                foreach ($line_item->basicProcessings as $basic) {
                    $dust += $basic->dust;
                    foreach ($basic->basicProcessingMaterials as $material) {
                        switch ($material->product_id) {
                            case 1:
                                $al += $material->qty;
                                break;
                            case 2:
                                $cu += $material->qty;
                                break;
                            case 3:
                                $iron += $material->qty;
                                break;
                            default:            
                                break;
                        }
                    }
                }
                foreach ($line_item->advancedProcessings as $adv) {
                    $dust += $adv->dust;
                    foreach ($adv->advancedProcessingProducts as $product) {
                        switch ($product->product_id) {
                            case 4:
                                $ingot += $product->qty;
                                break;
                            case 5:
                                $kitty += $product->qty;
                                break;
                            default:            
                                break;
                        }
                    }
                }
            }
        }

        return [
            Stat::make('Aluminium',Number::format($al).' Kg' ),
            Stat::make('Copper', Number::format($cu).' Kg' ),
            Stat::make('Iron', Number::format($iron).' Kg' ),
            Stat::make('Dust', Number::format($dust).' Kg' ),
            Stat::make('Alumimium Ingot', Number::format($ingot).' Kg' ),
            Stat::make('Kitty', Number::format($kitty).' Kg' ),
        ];
    }
}
