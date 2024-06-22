<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\AdvancedProcessingProduct;
use App\Models\BasicProcessing;
use App\Models\BasicProcessingMaterial;
use App\Models\Material;
use App\Models\ProcessedProduct;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\ViewEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdvancedprocessingView extends Component implements HasForms, HasInfolists , HasTable
{
    use InteractsWithTable;
    use InteractsWithInfolists;
    use InteractsWithForms;
    
    public AdvancedProcessing $advProcessing ;

    public function  mount($id)
    {
        $this->advProcessing = AdvancedProcessing::find($id);    
    }


    public function productionInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->advProcessing)
            ->schema([
                Split::make([
                    Section::make('Details')
                    ->headerActions([
                        Action::make('Process')
                            ->modalWidth(MaxWidth::Small)
                            ->icon('heroicon-o-clipboard')
                            ->visible(fn () : bool => $this->advProcessing->un_processed_quantity > 0)
                            ->form([
                                Select::make('product')
                                ->options(ProcessedProduct::all()->pluck('name','id')),
                                TextInput::make('quantity')
                                ->suffix('Kg')
                                ->rules([
                                    fn (): Closure => function (string $attribute, $value, Closure $fail ) {
                                        if ($value > $this->advProcessing->un_processed_quantity ) {
                                            $fail('The quantity is invalid.');
                                        }
                                    },
                                ])
                                ->required(),
                            ])
                            ->action(function ($data) {
                                $this->advProcessing->products()->create([
                                    'processed_product_id'=>$data['product'],                     
                                    'qty'=>$data['quantity'],                     
                                    'date'=>now(),                     
                                ]);
                                $this->advProcessing->update(['processed'=>$this->advProcessing->processed + $data['quantity']]);
                                if ($this->advProcessing->un_processed_quantity == 0) {
                                    $this->advProcessing->update(['end_date'=>now()]);
                                }
                                Notification::make()
                                ->title('Processed successfully')
                                ->success()
                                ->send();
                            })
                    ])
                    ->schema([
                        TextEntry::make('material.name')
                        ->inlineLabel()
                        ->weight(FontWeight::Black),
                        Grid::make(3)
                        ->schema([
                            TextEntry::make('qty')
                            ->suffix(' Kg')
                            ->label('Total Quantity'),
                            TextEntry::make('processed')
                            ->suffix(' Kg')
                            ->label('Processed Quantity'),
                            TextEntry::make('un_processed_quantity')
                            ->label('UnProcessed Quantity')
                            ->suffix(' Kg')
                            ->numeric(decimalPlaces: 3),
                        ]),
                        Grid::make(3)
                        ->schema([
                            TextEntry::make('start_date')
                            ->date(),
                            TextEntry::make('end_date')                            
                            ->placeholder('--')                          
                            ->date(),
                        ]),
                        Fieldset::make('Processed Details')
                        ->schema([
                            ViewEntry::make('product-details')
                            ->label('')
                            ->view('infolists.components.advanced-processing-products-table')
                        ])->columns(1),
                    ]),
                    Section::make([
                        ViewEntry::make('id')
                        ->label('Chart')
                        ->view('infolists.components.advanced-processing-chart')
                    ])->grow(false),
                ])->from('md'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->striped()
            ->query(AdvancedProcessingProduct::query()->where('advanced_processing_id',$this->advProcessing->id))
            ->columns([
                TextColumn::make('')
                ->rowIndex(),
                TextColumn::make('date')
                    ->date('d-m-y')
                    ->weight(FontWeight::Medium)
                    ->size(TextColumnSize::Medium),
                TextColumn::make('processedProduct.name')
                    ->weight(FontWeight::Bold)
                    ->size(TextColumnSize::Medium),
                TextColumn::make('qty')
                    ->numeric()
                    ->suffix('Kg')
                    ->weight(FontWeight::Medium)
                    ->size(TextColumnSize::Medium)
                    ->summarize(Sum::make()->label('Total')->numeric(decimalPlaces: 3)),
            ])
            ->paginated(false)
            ->defaultGroup('processedProduct.name')
            ->defaultSort('created_at', 'acs');;
    }

    public function render()
    {
        return view('livewire.advancedprocessing-view');
    }
}
