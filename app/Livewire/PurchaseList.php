<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\BasicProcessing;
use App\Models\Scrap;
use App\Models\Purchase;
use App\Models\Vendor;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\Textarea;;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PurchaseList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Purchase')
            ->striped()
            ->query(Purchase::query())
            ->headerActions([
                CreateAction::make()
                ->form([
                    Grid::make(3)
                        ->schema([
                            DatePicker::make('date')
                                ->default(now())
                                ->required(),
                            Select::make('vendor_id')
                                ->label('Vendor')
                                ->required()
                                ->relationship('vendor', 'name')
                                ->createOptionForm([
                                    Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->unique(Vendor::class)
                                            ->required()
                                            ->maxLength(30),
                                        TextInput::make('contact_person')
                                            ->maxLength(30),                                            
                                    ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('email')
                                                ->email(),
                                            TextInput::make('mobile')
                                                ->tel()
                                                ->maxLength(10),
                                        ]),
                                    Grid::make(1)
                                        ->schema([
                                            Textarea::make('address')
                                            ->maxLength(255),
                                        ]),
                                ])
                                ->options(Vendor::all()->pluck('name','id'))
                                ->searchable()
                                ->native(false),
                            TextInput::make('ref')
                                ->label('Reference')
                                ->autocomplete(false),
                        ]),
                    TableRepeater::make('lineItems')
                        ->label('Products')
                        ->relationship('lineItems')
                        ->schema([
                            Select::make('scrap_id')
                                ->label('Product')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->options(Scrap::all()->pluck('name','id'))
                                ->native(false)
                                ->required(),
                            TextInput::make('qty')
                                ->numeric()
                                ->suffix('Kg')
                                ->autocomplete(false)
                                ->required(),
                        ])->saveRelationshipsUsing(function (Model $record, $state){
                            foreach ($state as $relation) {
                                $scrap = Scrap::find($relation['scrap_id']);
                                $scrap->update(['stock'=>$scrap->stock + $relation['qty']]);
                            }
                            $record->lineItems()->createMany($state);
                        })                   
                        ->defaultItems(1)
                        ->addActionLabel('Add more products')
                        ->addable(false)
                        ->columns(2),
                ])->slideOver(),  
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('date')
                    ->weight(FontWeight::Black)
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('vendor.name')
                    ->weight(FontWeight::Bold)
                    ->color(Color::Blue)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ref')
                    ->label('#Reference')
                    ->placeholder('--'),
                TextColumn::make('firstLineItem.scrap.name')
                    ->label('Scrap')
                    ->listWithLineBreaks()
                    ->bulleted(),
                TextColumn::make('firstLineItem.qty')
                    ->label('Quantity')
                    ->suffix(' Kg')
                    ->numeric()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('date')
                ->form([
                    DatePicker::make('created_from')->native(false),
                    DatePicker::make('created_until')->native(false),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
             
                    if ($data['created_from'] ?? null) {
                        $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                            ->removeField('created_from');
                    }
             
                    if ($data['created_until'] ?? null) {
                        $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                            ->removeField('created_until');
                    }
             
                    return $indicators;
                })
            ])
            ->actions([
                Action::make('Start Basic Process')
                ->button()
                ->visible(fn($record)=>$record->firstLineItem->scrap->is_base && $record->firstLineItem->basicProcessings->count() == 0)
                ->action(function($record){
                    $basic = BasicProcessing::create([
                        'scrap_id'=>$record->firstLineItem->scrap->id,
                        'qty'=>$record->firstLineItem->qty,
                        'purchase_line_item_id'=>$record->firstLineItem->id,
                        'start_date'=>now()
                    ]);
                    $this->redirectRoute('basic-processings.view',['id'=>$basic->id]);
                }),
                Action::make('Basic Process Finished')
                ->label(function($record){
                    return $record->firstLineItem->basicProcessings->first()->end_date == null ? 'Basic Processing Started' : 'Basic Process Finished';
                })
                ->button()
                ->visible(fn($record)=>$record->firstLineItem->scrap->is_base && $record->firstLineItem->basicProcessings->count() > 0)
                ->url(fn($record)=>route('basic-processings.view',['id'=>$record->firstLineItem->basicProcessings->first()->id])),
                Action::make('Start Aluminium Process')
                ->color('warning')
                ->button()
                ->visible(fn($record)=>!$record->firstLineItem->scrap->is_base && $record->firstLineItem->advancedProcessings->count() == 0)
                ->action(function($record){
                    $adv = AdvancedProcessing::create([
                        'scrap_id'=>$record->firstLineItem->scrap->id,
                        'qty'=>$record->firstLineItem->qty,
                        'purchase_line_item_id'=>$record->firstLineItem->id,
                        'start_date'=>now()
                    ]);
                    $this->redirectRoute('advanced-processings.view',['id'=>$adv->id]);
                }),
                Action::make('Aluminium Process Finished')
                ->button()
                ->label(function($record){
                    return $record->firstLineItem->advancedProcessings->first()->end_date == null ? 'Aluminium Processing Started' : 'Aluminium Process Finished';
                })
                ->color('warning')
                ->visible(fn($record)=>$record->firstLineItem->advancedProcessings->count() > 0)
                ->url(fn($record)=>route('advanced-processings.view',['id'=>$record->firstLineItem->advancedProcessings->first()->id])),    
            ])
            // ->recordUrl(
            //     fn (Purchase $record): string => route('purchases.view', $record->id),
            // )
            ->defaultSort('created_at', 'desc');
    }
    public function render()
    {
        return view('livewire.purchase-list');
    }
}
