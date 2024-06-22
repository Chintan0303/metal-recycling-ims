<?php

namespace App\Livewire;

use App\Models\BasicProcessingMaterial;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DashboardBasicChart extends ChartWidget
{
    protected static ?string $heading = 'Basic Processing Report';
    public ?string $filter = 'year';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $dateRange = $this->getDateRange($activeFilter);
        $startDate = $dateRange['start']->format('Y-m-d H:i:s');
        $endDate = $dateRange['end']->format('Y-m-d H:i:s');


        $alSumQty = BasicProcessingMaterial::join('materials', 'basic_processing_materials.material_id', '=', 'materials.id')
                    ->where('materials.name', 'Aluminum')
                    ->whereBetween('basic_processing_materials.date', [$startDate, $endDate])
                    ->sum('basic_processing_materials.qty');
        $cuSumQty = BasicProcessingMaterial::join('materials', 'basic_processing_materials.material_id', '=', 'materials.id')
                    ->where('materials.name', 'Copper')
                    ->whereBetween('basic_processing_materials.date', [$startDate, $endDate])
                    ->sum('basic_processing_materials.qty');
        $feSumQty = BasicProcessingMaterial::join('materials', 'basic_processing_materials.material_id', '=', 'materials.id')
                    ->where('materials.name', 'Iron')
                    ->whereBetween('basic_processing_materials.date', [$startDate, $endDate])
                    ->sum('basic_processing_materials.qty');
        $dustSumQty = BasicProcessingMaterial::join('materials', 'basic_processing_materials.material_id', '=', 'materials.id')
                    ->where('materials.name', 'Dust')
                    ->whereBetween('basic_processing_materials.date', [$startDate, $endDate])
                    ->sum('basic_processing_materials.qty');
        return [
           'datasets' => [
                [
                    'label' => 'Processed',
                    'data' => [$alSumQty,$cuSumQty,$feSumQty,$dustSumQty],
                    'backgroundColor'=> [
                        'rgb(255, 99, 132 , 0.2)',
                        'rgb(54, 162, 235 , 0.2)',
                        'rgb(255, 205, 86 , 0.2)',
                        'rgb(255, 205, 186 , 0.2)',
                      ],
                      'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 205, 186)',
                      ],
                ],
            ],
            'labels' => ['Aluminum', 'Copper' , 'Iron' , 'Dust'],

        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'last_week' => 'Last week',
            'week' => 'This Week',
            'last_month' => 'Last month',
            'month' => 'This Month',
            'year' => 'This year',
        ];
    }

    protected function getDateRange($filter): array
    {
        $now = Carbon::now();

        switch ($filter) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'last_week':
                $start = $now->copy()->subWeek()->startOfWeek();
                $end = $now->copy()->subWeek()->endOfWeek();
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                break;
            default:
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
        }    

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
    

    public function getDescription(): ?string
    {
        return 'The total of materials processed from scrap products.';
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
