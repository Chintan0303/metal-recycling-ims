<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class AdvancedProcessingChart extends ChartWidget
{
    public $id;
    public AdvancedProcessing $processing;
    public $percentage_data = [];
    protected static ?string $pollingInterval = '10s';
    
    public function mount() : void 
    {
        $this->processing = AdvancedProcessing::find($this->id); 

    }
    
    protected function getData(): array
    {
        $al = 0 ; $cu = 0 ; $dust = 0;
        foreach ($this->processing->advancedProcessingProducts as $product) {
            switch ($product->product->id) {
                case '4':
                    $al += $product->qty;
                    break;
                case '5':
                    $cu += $product->qty;
                        
                    break;
                default:

                    break;
            }
        }
        $this->percentage_data = [
            ($al*100)/$this->processing->qty,
            ($cu*100)/$this->processing->qty,
            ($this->processing->dust*100)/$this->processing->qty ,
            ($this->processing->un_processed_quantity*100)/$this->processing->qty 
        ]; 

        return [
            'datasets' => [
                [
                    // 'label' => 'Blog posts created',
                    'data' => $this->percentage_data,
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 186)',
                        'rgb(255, 205, 240)'
                    ],
                ],
            ],
            'labels' => [
                'Aluminium Ingot'.'('.Number::percentage(($al*100)/$this->processing->qty,precision: 2).')',
                'Kitty'.'('.Number::percentage(($cu*100)/$this->processing->qty, precision:2).')',
                'Dust' .'('.Number::percentage(($this->processing->dust*100)/$this->processing->qty , precision:2).')',
                'Unprocessed'.'('.Number::percentage(($this->processing->un_processed_quantity*100)/$this->processing->qty, precision:2).')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
