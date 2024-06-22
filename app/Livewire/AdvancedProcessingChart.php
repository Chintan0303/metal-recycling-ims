<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use Filament\Widgets\ChartWidget;

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
        foreach ($this->processing->products as $product) {
            switch ($product->processedProduct->name) {
                case 'Aluminium Ingot':
                    $al += $product->qty;      
                    break;
                case 'Dress':
                    $cu += $product->qty;      
                        
                    break;
                case 'Dust':
                    $dust += $product->qty;      

                    break;
                
                default:

                    break;
            }
        }
        $this->percentage_data = [
            ($al*100)/$this->processing->qty,
            ($cu*100)/$this->processing->qty,
            ($dust*100)/$this->processing->qty ,
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
                'Aluminium Ingot'.'('.($al*100)/$this->processing->qty.' %)',
                'Dress'.'('.($cu*100)/$this->processing->qty.' %)',
                'Dust' .'('.($dust*100)/$this->processing->qty.' %)',
                'Unprocessed'.'('.($this->processing->un_processed_quantity*100)/$this->processing->qty.' %)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
