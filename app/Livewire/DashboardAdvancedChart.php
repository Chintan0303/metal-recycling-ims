<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\AdvancedProcessingProduct;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DashboardAdvancedChart extends ChartWidget
{
    protected static ?string $heading = 'Aluminium Processing Report';
    public ?string $filter = 'year';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $dateRange = $this->getDateRange($activeFilter);
        $startDate = $dateRange['start']->format('Y-m-d H:i:s');
        $endDate = $dateRange['end']->format('Y-m-d H:i:s');


        $alSumQty = AdvancedProcessingProduct::join('products', 'advanced_processing_products.product_id', '=', 'products.id')
                    ->where('products.id', 4)
                    ->whereBetween('advanced_processing_products.date', [$startDate, $endDate])
                    ->sum('advanced_processing_products.qty');
        $cuSumQty = AdvancedProcessingProduct::join('products', 'advanced_processing_products.product_id', '=', 'products.id')
                    ->where('products.id', 5)
                    ->whereBetween('advanced_processing_products.date', [$startDate, $endDate])
                    ->sum('advanced_processing_products.qty');
        $dustSumQty = AdvancedProcessing::whereBetween('start_date', [$startDate, $endDate])
                    ->sum('dust');
        return [
           'datasets' => [
                [
                    'label' => 'Processed',
                    'data' => [$alSumQty,$cuSumQty, $dustSumQty],
                    'backgroundColor'=> [
                        'rgb(255, 99, 132 , 0.2)',
                        'rgb(54, 162, 235 , 0.2)',
                        'rgb(255, 205, 186 , 0.2)',
                      ],
                      'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 186)',
                      ],
                ],
            ],
            'labels' => ['Aluminium Ingot', 'Dress' , 'Dust'],

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
        return 'The total of products processed from aluminium.';
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
