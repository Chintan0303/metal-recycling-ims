<?php

namespace App\Livewire;

use App\Models\BasicProcessing;
use App\Models\Scrap;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BasicProcessingList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Basic Processings')
            ->striped()
            ->query(BasicProcessing::query())
            ->headerActions([
                // CreateAction::make()
                // ->form([
                //     Select::make('scrap_id')
                //     ->label('Scrap product')
                //     ->searchable()
                //     ->preload()
                //     ->native(false)
                //     ->options(Scrap::all()->pluck('name','id'))
                //     ->required(),
                //     TextInput::make('qty')
                //     ->numeric()
                //     ->suffix('Kg')
                //     ->required(),
                //     DatePicker::make('start_date')
                //     ->native(false)
                //     ->default(today())
                // ])
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('scrap.name')
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
                    // 'Completed In Time' => 'success',
                    // '5' => 'gray',
                    // '6' => 'success',
                    // '7' => 'danger',
                }) ,
            ])
            ->actions([
                // ActionGroup::make([
                //     Action::make('execute pending quantity')
                //     ->color('warning')
                //     ->visible(fn($record)=>$record->pending_quantity > 0 && $record->completed_at == null)
                //     ->icon('heroicon-o-clipboard')
                //     ->action(function ($record) {
                //         $errors = [];
                //         foreach ($record->productionMaterialRecipes as $materialRecipe) {
                //             $materialCurrentStock = $materialRecipe->material->stock;
                //             if ($materialCurrentStock < $materialRecipe->qty*$record->pending_quantity) {
                //                 $errors[] = $materialRecipe->material->name . ' current stock is insuffecient.';
                //             }
                //         }
                //         foreach ($record->productionProductRecipes as $productRecipe) {
                //             $productCurrentStock = $productRecipe->product->stock;
                //             if ($productCurrentStock < $productRecipe->qty*$record->pending_quantity) {
                //                 $errors[] = $productRecipe->product->name . ' current stock is insuffecient.';
                //             }
                //         }
                //         if (count($errors)) {
                //             foreach ($errors as $error) {
                //                 Notification::make()
                //                 ->danger()
                //                 ->body($error)
                //                 ->send();
                //             }
                //             return;
                //         }
                //         foreach ($record->productionMaterialRecipes as $materialRecipe) {
                //             $materialRecipe->material->update(['stock'=>$materialRecipe->material->stock - ($materialRecipe->qty*$record->pending_quantity)]);
                //             MaterialStockTransactions::dispatch([
                //                 'main_type'=>'Production',
                //                 'type'=>'qty_executed',
                //                 'production_id'=>$record->id,
                //                 'stock_reduced'=>$materialRecipe->qty*$record->pending_quantity,
                //                 'current_stock'=>$materialRecipe->material->stock,
                //                 'material_id'=>$materialRecipe->material->id,
                //             ]);
                //         }
                //         foreach ($record->productionProductRecipes as $productRecipe) {
                //             $productRecipe->product->update(['stock'=>$productRecipe->product->stock - ($productRecipe->qty*$record->pending_quantity)]);
                //             ProductTransaction::dispatch([
                //                 'main_type'=>'Production',
                //                 'type'=>'qty_executed',
                //                 'production_id'=>$record->id,
                //                 'stock_reduced'=>$productRecipe->qty*$record->pending_quantity,
                //                 'current_stock'=>$productRecipe->material->stock,
                //                 'product_id'=>$productRecipe->product->id,
                //             ]);
                //         }
                //         $record->product->update(['stock'=>$record->product->stock + $record->pending_quantity]);
                //         ProductTransaction::dispatch([
                //             'main_type'=>'Production',
                //             'type'=>'main_product_qty_executed',
                //             'production_id'=>$record->id,
                //             'stock_increased'=>$record->pending_quantity,
                //             'current_stock'=> $record->product->stock,
                //             'product_id'=> $record->product->id,
                //         ]);
                //         ExecutedQuantity::create([
                //             'qty'=>$record->pending_quantity,
                //             'production_id'=>$record->id,
                //         ]);
                //         $record->update(['executed_qty'=>$record->executed_qty + $record->pending_quantity]);
                //         if ($record->pending_quantity == 0) {
                //             $record->update(['completed_at'=>now()]);
                //         }
                //     }),
                //     Action::make('mark as completed')
                //     ->color('success')
                //     ->visible(fn($record)=>$record->completed_at == null)
                //     ->icon('heroicon-o-check-circle')
                //     ->requiresConfirmation()
                //     ->modalHidden(fn ($record): bool =>$record->pending_quantity == 0 )
                //     ->modalHeading('Mark As Completed')
                //     ->modalDescription('There is pending quantity in these production . Are you sure you\'d like to complete this production line ?')
                //     ->modalSubmitActionLabel('Yes, mark as completed')
                //     ->action(function ($record){
                //         $record->update(['completed_at'=>now()]);
                //     }),
                //     DeleteAction::make()
                //     ->requiresConfirmation()
                // ]),
            ])
            ->filters([
            ])
            ->recordUrl(fn($record)=>route('basic-processings.view',['id'=>$record->id]))
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.basic-processing-list');
    }
}
