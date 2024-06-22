<?php

namespace App\Livewire;

use App\Models\BasicProcessing;
use Filament\Widgets\ChartWidget;

class BasicProcessingChart extends ChartWidget
{
    // protected static ?string $heading = 'test';
    public $id;
    public BasicProcessing $processing;
    public $percentage_data = [];
    protected static ?string $pollingInterval = '10s';
    
    public function mount() : void 
    {
        $this->processing = BasicProcessing::find($this->id); 

    }
    
    protected function getData(): array
    {
        $al = 0 ; $cu = 0 ; $iron = 0; $dust = 0;
        foreach ($this->processing->materials as $material) {
            switch ($material->material->name) {
                case 'Aluminum':
                    $al += $material->qty;      
                    break;
                case 'Copper':
                    $cu += $material->qty;      
                        
                    break;
                case 'Iron':
                    $iron += $material->qty;      
                    
                    break;
                case 'Dust':
                    $dust += $material->qty;      

                    break;
                
                default:

                    break;
            }
        }
        $this->percentage_data = [
            ($al*100)/$this->processing->qty,
            ($cu*100)/$this->processing->qty,
            ($iron*100)/$this->processing->qty,
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
                        'rgb(255, 205, 86)',
                        'rgb(255, 205, 186)',
                        'rgb(255, 205, 240)'
                    ],
                ],
            ],
            'labels' => [
                'Aluminium'.'('.($al*100)/$this->processing->qty.' %)',
                'Copper'.'('.($cu*100)/$this->processing->qty.' %)',
                'Iron'.'('.($iron*100)/$this->processing->qty.' %)',
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
