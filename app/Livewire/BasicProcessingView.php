<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\BasicProcessing;
use App\Models\BasicProcessingMaterial;
use App\Models\Material;
use App\Models\Product;
use App\Rules\SumLessThan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Closure;
use Filament\Forms\Components\Grid as ComponentsGrid;
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
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BasicProcessingView extends Component implements HasForms, HasInfolists , HasTable
{
    use InteractsWithTable;
    use InteractsWithInfolists;
    use InteractsWithForms;
    
    public BasicProcessing $basicProcessing ;

    public function  mount($id)
    {
        $basic = BasicProcessing::find($id);
        if (!$basic) {
            abort(404);
        }    
        $this->basicProcessing = $basic; 
    }


    public function productionInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->basicProcessing)
            ->schema([
                Split::make([
                    Section::make('Details')
                    ->headerActions([
                        Action::make('Process Material')
                        ->modalWidth(MaxWidth::Small)
                        ->icon('heroicon-o-clipboard')
                        ->visible(fn () : bool => $this->basicProcessing->un_processed_quantity > 0)
                        ->form([
                            TextInput::make('aluminium')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('copper,iron,dust'),
                            TextInput::make('copper')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('aluminium,iron,dust'),
                            TextInput::make('iron')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('aluminium,copper,dust'),
                            TextInput::make('dust')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('aluminium,iron,copper'),
                        ])
                        ->action(function (Action $action , $data) {
                            $sum = $data['aluminium'] + $data['copper'] + $data['iron'] + $data['dust'];
                            if ($sum > $this->basicProcessing->un_processed_quantity) {
                                Notification::make()
                                ->warning()
                                ->title('Total can\'t be more than '.$this->basicProcessing->un_processed_quantity.'Kg.')
                                ->persistent()
                                ->send();
                                $action->halt();
                            }
                            if ($data['aluminium']) {
                                $this->basicProcessing->basicProcessingMaterials()->create([
                                    'product_id'=>1,                     
                                    'qty'=>$data['aluminium'],                     
                                    'date'=>now(),                     
                                ]);
                                $aluminium = Product::find(1);
                                $aluminium->update(['stock'=> $aluminium->stock + $data['aluminium']] );
                            }
                            if ($data['copper']) {
                                $this->basicProcessing->basicProcessingMaterials()->create([
                                    'product_id'=>2,                     
                                    'qty'=>$data['copper'],                     
                                    'date'=>now(),                     
                                ]);
                                $copper = Product::find(2);
                                $copper->update(['stock'=> $copper->stock + $data['copper']] );
                            }
                            if ($data['iron']) {
                                $this->basicProcessing->basicProcessingMaterials()->create([
                                    'product_id'=>3,                     
                                    'qty'=>$data['iron'],                     
                                    'date'=>now(),                     
                                ]);
                                $iron = Product::find(3);
                                $iron->update(['stock'=> $iron->stock + $data['iron']] );
                            }
                            if ($data['dust']) {
                                $this->basicProcessing->update(['dust'=>$this->basicProcessing->dust + $data['dust']]);
                            }
                            $this->basicProcessing->update(['processed'=>$this->basicProcessing->processed + $sum]);
                            $this->basicProcessing->scrap->update([
                                'stock'=> $this->basicProcessing->scrap->stock - $sum
                            ]);

                            if ($this->basicProcessing->un_processed_quantity == 0) {
                                $this->basicProcessing->update(['end_date'=>now()]);
                                $this->redirectRoute('basic-processings.view', ['id' => $this->basicProcessing->id]);
                            }
                            Notification::make()
                            ->title('Processed successfully')
                            ->success()
                            ->send();
                        }),
                        // Action::make('Process Dust')
                        // ->modalWidth(MaxWidth::Small)
                        // ->icon('heroicon-o-funnel')
                        // ->visible(fn () : bool => $this->basicProcessing->un_processed_quantity > 0)
                        // ->form([
                        //     TextInput::make('quantity')
                        //     ->suffix('Kg')
                        //     ->rules([
                        //         fn (): Closure => function (string $attribute, $value, Closure $fail ) {
                        //             if ($value > $this->basicProcessing->un_processed_quantity ) {
                        //                 $fail('The quantity is invalid.');
                        //             }
                        //         },
                        //     ])
                        //     ->required(),
                        // ])
                        // ->action(function ($data) {
                        //     $this->basicProcessing->update(['processed'=>$this->basicProcessing->processed + $data['quantity']]);
                        //     $this->basicProcessing->update(['dust'=>$this->basicProcessing->dust + $data['quantity']]);
                        //     if ($this->basicProcessing->un_processed_quantity == 0) {
                        //         $this->basicProcessing->update(['end_date'=>now()]);
                        //         $this->redirectRoute('basic-processings.view', ['id' => $this->basicProcessing->id]);
                        //     }
                        //     Notification::make()
                        //     ->title('Processed successfully')
                        //     ->success()
                        //     ->send();
                        // })
                    ])
                    ->schema([
                        Fieldset::make('')
                            ->schema([
                                TextEntry::make('scrap.name')
                                ->weight(FontWeight::Black),                                
                                TextEntry::make('purchaseLineItem.purchase.vendor.name')
                                ->label('Vendor')
                                ->weight(FontWeight::Black),                                
                                TextEntry::make('purchaseLineItem.purchase.id')
                                ->label('Purchase #')
                                ->weight(FontWeight::Black)
                                ->url(fn($state)=>route('purchases.view',$state)),
                                TextEntry::make('purchaseLineItem.purchase.date')
                                ->label('Purchase Date')
                                ->date('d-m-Y')
                                ->weight(FontWeight::Black),
                                TextEntry::make('qty')
                                ->badge()
                                ->suffix(' Kg')
                                ->numeric()
                                ->label('Total Quantity'),                           
                            ])->columns(5),
                        Fieldset::make('')
                            ->schema([
                                Grid::make(3)
                                ->schema([
                                    TextEntry::make('processed')
                                    ->suffix(' Kg')
                                    ->badge()
                                    ->numeric()
                                    ->label('Processed Quantity'),
                                    TextEntry::make('un_processed_quantity')
                                    ->badge()
                                    ->numeric()
                                    ->color('danger')
                                    ->label('UnProcessed Quantity')
                                    ->suffix(' Kg'),
                                    TextEntry::make('dust')
                                    ->badge()
                                    ->color('warning')
                                    ->suffix(' Kg')
                                    ->numeric(),
                                ])->columns(3),
                                Grid::make(3)
                                ->schema([
                                    TextEntry::make('start_date')
                                    ->date(),
                                    TextEntry::make('end_date')                            
                                    ->placeholder('--')                          
                                    ->date(),
                                    TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {                    
                                        'In Progress' => 'warning',
                                        'Processed' => 'success',
                                    }) ,     
                                ]),
                                
                            ])
                            ->columns(2),
                        ViewEntry::make('product-details')
                        ->label('')
                        ->view('infolists.components.basic-processing-materials-table')
                    ]),
                    Section::make([
                        ViewEntry::make('id')
                        ->label('Chart')
                        ->view('infolists.components.basic-processing-chart')
                    ])->grow(false),
                ])->from('md'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->striped()
            ->query(BasicProcessingMaterial::query()->where('basic_processing_id',$this->basicProcessing->id))
            ->headerActions([
                ActionsAction::make('Process Aluminium')
                ->color('warning')
                ->button()
                ->visible(fn()=>$this->basicProcessing->end_date !== null 
                        && $this->basicProcessing->un_processed_quantity == 0 
                        && $this->basicProcessing->advancedProcessings()->count() == 0
                        && Product::find(1)->stock ==  $this->getAluminiumTotalQty()
                        && $this->getAluminiumTotalQty() > 0)
                ->action(function($record){
                    $product = Product::find(1);
                    $product->update(['stock'=>$product->stock - $this->getAluminiumTotalQty() ]);
                    $adv = AdvancedProcessing::create([
                        'product_id'=>1,
                        'qty'=> $this->getAluminiumTotalQty(),
                        'purchase_line_item_id'=>$this->basicProcessing->purchase_line_item_id,
                        'basic_processing_id'=>$this->basicProcessing->id,
                        'start_date'=>now()
                    ]);
                    $this->redirectRoute('advanced-processings.view',['id'=>$adv->id]);
                }),
                ActionsAction::make('View Aluminium process details')
                ->color('warning')
                ->button()
                ->visible(fn()=>$this->basicProcessing->un_processed_quantity == 0 && $this->basicProcessing->advancedProcessings()->count())
                ->url(fn()=>route('advanced-processings.view',$this->basicProcessing->advancedProcessings()->first()->id))
            ])
            ->columns([
                TextColumn::make('')
                ->rowIndex(),
                TextColumn::make('date')
                    ->date('d-m-y')
                    ->weight(FontWeight::Medium)
                    ->size(TextColumnSize::Medium),
                TextColumn::make('product.name')
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
            ->defaultGroup('product.name')
            ->defaultSort('created_at', 'acs');;
    }

    public function getAluminiumTotalQty()
    {
        $basicProcessingMaterials = BasicProcessingMaterial::where('basic_processing_id',$this->basicProcessing->id)->where('product_id', 1)->get();
        return $basicProcessingMaterials->sum('qty');
        
    }


    public function render()
    {
        return view('livewire.basic-processing-view');
    }
}
