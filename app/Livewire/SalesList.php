<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Component;

#[Layout('layouts.app')]
class SalesList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Sales')
            ->striped()
            ->query(Sale::query())
            ->headerActions([
                CreateAction::make()
                ->form([
                    Grid::make(3)
                        ->schema([
                            DatePicker::make('date')
                                ->default(now())
                                ->required(),
                            Select::make('customer_id')
                                ->label('Customer')
                                ->required()
                                ->relationship('customer', 'name')
                                ->createOptionForm([
                                    Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->unique(Customer::class)
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
                                ->options(Customer::all()->pluck('name', 'id'))
                                ->searchable()->native(true),
                            TextInput::make('ref')
                                ->label('Reference')
                                ->autocomplete(false),
                        ]),
                    TableRepeater::make('lineItems')
                        ->label('Products')
                        ->relationship('lineItems')
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->options(Product::pluck('name','id'))
                                ->native(false)
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->required(),
                            TextInput::make('qty')
                                ->numeric()
                                ->suffix(' Kg')
                                ->rules([
                                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use($get) {
                                        $product = Product::find($get('product_id'));
                                        if ($value > $product->stock) {
                                            $fail('The product available stock is  '.$product->stock);
                                        }
                                    },                                       
                                ])
                                ->autocomplete(false)
                                ->required(),
                        ])->saveRelationshipsUsing(function (Model $record, $state){
                            foreach ($state as $relation) {
                                $product = Product::find($relation['product_id']);
                                $product->update(['stock'=>$product->stock - $relation['qty']]);
                            }
                            $record->lineItems()->createMany($state);
                        })                    
                        ->defaultItems(1)->addActionLabel('Add more products')->minItems(1)->columns(2),
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
                TextColumn::make('customer.name')
                    ->weight(FontWeight::Bold)
                    ->color(Color::Blue)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ref')
                    ->label('#Reference')
                    ->placeholder('--'),
                TextColumn::make('lineItems.product.name')
                    ->label('Products Sold')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
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
            // ->recordUrl(
            //     fn (Sale $record): string => route('sales.view', $record),
            // )
            ->defaultSort('created_at', 'desc');
    }
    public function render()
    {
        return view('livewire.sales-list');
    }
}
