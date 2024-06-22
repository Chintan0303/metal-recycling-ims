<?php

namespace App\Livewire;

use App\Models\ScrapProduct;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
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
                            Select::make('scrap_product_id')
                                ->label('Product')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->options(ScrapProduct::all()->pluck('name','id'))
                                ->native(false)
                                ->required(),
                            TextInput::make('qty')
                                ->numeric()
                                ->suffix('Kg')
                                ->autocomplete(false)
                                ->required(),
                        ])                  
                        ->defaultItems(1)
                        ->addActionLabel('Add more products')
                        ->minItems(1)
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
                TextColumn::make('lineItems.scrapProduct.name')
                    ->label('Products Received')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),
            
            ])
            ->actions([
                // DeleteAction::make()
                // ->requiresConfirmation()
                // ->modalDescription('Are you sure you\'d like to delete this receipt ? This cannot be undone.It will remove the stock from product.')
                // ->before(function ($record) {
                //     foreach ($record->line_items as $item) {
                //         $item->product->update(['stock'=>$item->product->stock - $item->qty]);
                //         ProductTransaction::dispatch([
                //             'main_type'=>'Purchase',
                //             'type'=>'purchase_deleted',
                //             'purchase_id'=>$record->id,
                //             'stock_reduced'=>$item->qty,
                //             'current_stock'=>$item->product->stock,
                //             'vendor_name'=>$record->vendor->name,
                //             'vendor_id'=>$record->vendor->id,
                //             'product_id'=>$item->product->id,
                //         ]);
                //     }
                // })->successNotification(
                //     Notification::make()
                //          ->danger()
                //          ->title('Deleted')
                //          ->body('The receipt has been deleted successfully.'),
                //  ),
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
            ->defaultSort('created_at', 'desc');
    }
    public function render()
    {
        return view('livewire.purchase-list');
    }
}
