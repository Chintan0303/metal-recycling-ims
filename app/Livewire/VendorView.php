<?php

namespace App\Livewire;

use App\Models\PurchaseLineItem;
use App\Models\Vendor;
use Closure;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class VendorView extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Purchases')
            ->striped()
            ->query(PurchaseLineItem::query())
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('purchase', function ($query) {
                $query->where('vendor_id', $this->vendor->id);
            }) )
            ->columns([
                TextColumn::make('purchase.id')
                ->prefix('#')
                ->label('#Purchase')
                ->weight(FontWeight::Bold)
                ->color(Color::Blue),
                TextColumn::make('purchase.date')
                ->date('d-m-Y')
                ->weight(FontWeight::Bold)
                ->color(Color::Blue),
                TextColumn::make('scrap.name')
                ->weight(FontWeight::Bold),
                TextColumn::make('qty')
                ->suffix( 'Kg')
                ->summarize(Sum::make('qty'))
                ->weight(FontWeight::Bold),
                TextColumn::make('is_basic_processed')
                ->label('Basic')
                ->badge()
                ->color(fn (string $state): string => match ($state) {                    
                    'In Progress' => 'warning',
                    'Processed' => 'success',
                    'Not Applicable' => 'gray',
                    // 'Completed In Time' => 'success',
                    // '5' => 'gray',
                    // '6' => 'success',
                    // '7' => 'danger',
                }) ,
                TextColumn::make('is_adv_processed')
                ->label('Aluminium')
                ->badge()
                ->color(fn (string $state): string => match ($state) {                    
                    'In Progress' => 'warning',
                    'Processed' => 'success',
                    'Not Applicable' => 'gray',
                    // 'Completed In Time' => 'success',
                    // '5' => 'gray',
                    // '6' => 'success',
                    // '7' => 'danger',
                }) ,
            ])
            ->defaultGroup('scrap.name')
            ->defaultSort('created_at', 'desc');
    }

    public Vendor $vendor;


    public function render()
    {
        return view('livewire.vendor-view');
    }
}
