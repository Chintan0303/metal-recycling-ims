<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdvancedProcessingList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aluminium Processings')
            ->striped()
            ->query(AdvancedProcessing::query())
            // ->headerActions([
            //     CreateAction::make()
            //     ->form([
            //         Select::make('material_id')
            //         ->label('Material')
            //         ->searchable()
            //         ->preload()
            //         ->native(false)
            //         ->options(Material::all()->pluck('name','id'))
            //         ->required(),
            //         TextInput::make('qty')
            //         ->numeric()
            //         ->suffix('Kg')
            //         ->required(),
            //         DatePicker::make('start_date')
            //         ->native(false)
            //         ->default(today())
            //     ])
            // ])
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('scrap.name')
                    ->placeholder('--')
                    ->weight(FontWeight::Black)
                    ->sortable(),
                TextColumn::make('product.name')
                    ->placeholder('--')
                    ->weight(FontWeight::Black)
                    ->sortable(),
                TextColumn::make('purchaseLineItem.purchase.id')
                    ->prefix('#')
                    ->url(fn($state)=>  route('purchases.view',['purchase'=>$state]))
                    ->weight(FontWeight::Black)
                    ->sortable(),
                TextColumn::make('purchaseLineItem.purchase.date')
                    ->label('Purchase Date')
                    ->date('d-m-Y')
                    ->weight(FontWeight::Black)
                    ->sortable(),
                TextColumn::make('qty'),
                TextColumn::make('processed')
                ->placeholder('0.00'),
                TextColumn::make('start_date')
                ->date('d-m-Y'),
                TextColumn::make('end_date')
                ->placeholder('--')
                ->date('d-m-Y'),
                TextColumn::make('status')->badge()
                 ->color(fn (string $state): string => match ($state) {                    
                    'In Progress' => 'warning',
                    'Processed' => 'success',
                }) ,
            ])
            ->actions([
            ])
            ->filters([
            ])
            ->recordUrl(fn($record)=>route('advanced-processings.view',['id'=>$record->id]))
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.advanced-processing-list');
    }
}
