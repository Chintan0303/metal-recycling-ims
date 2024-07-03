<?php

namespace App\Livewire;

use App\Models\BasicProcessing;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

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
        foreach ($this->processing->basicProcessingMaterials as $material) {
            switch ($material->product->id) {
                case '1':
                    $al += $material->qty;      
                    break;
                case '2':
                    $cu += $material->qty;      
                        
                    break;
                case '3':
                    $iron += $material->qty;      
                    
                    break;                
                default:

                    break;
            }
        }
        $this->percentage_data = [
            ($al*100)/$this->processing->qty,
            ($cu*100)/$this->processing->qty,
            ($iron*100)/$this->processing->qty,
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
                        'rgb(255, 205, 86)',
                        'rgb(255, 205, 186)',
                        'rgb(255, 205, 240)'
                    ],
                ],
            ],
            'labels' => [
                'Aluminium'.'('.Number::percentage(($al*100)/$this->processing->qty, precision:2).')',
                'Copper'.'('.Number::percentage(($cu*100)/$this->processing->qty, precision:2).')',
                'Iron'.'('.Number::percentage(($iron*100)/$this->processing->qty, precision:2).')',
                'Dust' .'('.Number::percentage(($this->processing->dust*100)/$this->processing->qty, precision:2).')',
                'Unprocessed'.'('.Number::percentage(($this->processing->un_processed_quantity*100)/$this->processing->qty, precision:2).')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
