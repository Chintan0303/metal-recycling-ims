<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\BasicProcessing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $basicInProgress = BasicProcessing::whereNull('end_date')->count();
        $advInProgress = AdvancedProcessing::whereNull('end_date')->count();

        return [
            Stat::make('Basic Processing In Progress', $basicInProgress),
            Stat::make('Alumimium Processing In Progress', $advInProgress),
        ];
    }
}
