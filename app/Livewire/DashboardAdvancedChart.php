<?php

namespace App\Livewire;

use App\Models\AdvancedProcessingProduct;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DashboardAdvancedChart extends ChartWidget
{
    protected static ?string $heading = 'Advanced Processing Report';
    public ?string $filter = 'year';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $dateRange = $this->getDateRange($activeFilter);
        $startDate = $dateRange['start']->format('Y-m-d H:i:s');
        $endDate = $dateRange['end']->format('Y-m-d H:i:s');


        $alSumQty = AdvancedProcessingProduct::join('processed_products', 'advanced_processing_products.processed_product_id', '=', 'processed_products.id')
                    ->where('processed_products.name', 'Aluminium Ingot')
                    ->whereBetween('advanced_processing_products.date', [$startDate, $endDate])
                    ->sum('advanced_processing_products.qty');
        $cuSumQty = AdvancedProcessingProduct::join('processed_products', 'advanced_processing_products.processed_product_id', '=', 'processed_products.id')
                    ->where('processed_products.name', 'Dress')
                    ->whereBetween('advanced_processing_products.date', [$startDate, $endDate])
                    ->sum('advanced_processing_products.qty');
        $dustSumQty = AdvancedProcessingProduct::join('processed_products', 'advanced_processing_products.processed_product_id', '=', 'processed_products.id')
                    ->where('processed_products.name', 'Dust')
                    ->whereBetween('advanced_processing_products.date', [$startDate, $endDate])
                    ->sum('advanced_processing_products.qty');
        return [
           'datasets' => [
                [
                    'label' => 'Processed',
                    'data' => [$alSumQty,$cuSumQty,$dustSumQty],
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
