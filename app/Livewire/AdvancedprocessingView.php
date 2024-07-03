<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\AdvancedProcessingProduct;
use App\Models\BasicProcessing;
use App\Models\BasicProcessingMaterial;
use App\Models\Material;
use App\Models\ProcessedProduct;
use App\Models\Product;
use App\Models\Scrap;
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
        $adv = AdvancedProcessing::find($id);   
        if (!$adv) {
            abort(404,'Page not found');
        }
        $this->advProcessing = $adv;

    }


    public function productionInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->advProcessing)
            ->schema([
                Split::make([
                    Section::make('Details')
                    ->headerActions([
                        Action::make('Process Product')
                        ->modalWidth(MaxWidth::Small)
                        ->icon('heroicon-o-clipboard')
                        ->visible(fn () : bool => $this->advProcessing->un_processed_quantity > 0)
                        ->form([
                            TextInput::make('aluminium_ingot')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('kitty,dust'),
                            TextInput::make('kitty')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('aluminium_ingot,dust'),
                            TextInput::make('dust')
                            ->suffix('Kg')
                            ->numeric()
                            ->requiredWithoutAll('aluminium_ingot,kitty'),
                        ])
                        ->action(function (Action $action , $data) {
                            $sum = $data['aluminium_ingot'] + $data['kitty']  + $data['dust'];
                            if ($sum > $this->advProcessing->un_processed_quantity) {
                                Notification::make()
                                ->warning()
                                ->title('Total can\'t be more than '.$this->advProcessing->un_processed_quantity.'Kg.')
                                ->persistent()
                                ->send();
                                $action->halt();
                            }

                            if ($data['aluminium_ingot']) {
                                $this->advProcessing->advancedProcessingProducts()->create([
                                    'product_id'=>4,                     
                                    'qty'=>$data['aluminium_ingot'],                     
                                    'date'=>now(),                     
                                ]);
                                $aluminium_ingot = Product::find(4);
                                $aluminium_ingot->update(['stock'=> $aluminium_ingot->stock + $data['aluminium_ingot']] );
                            }
                            if ($data['kitty']) {
                                $this->advProcessing->advancedProcessingProducts()->create([
                                    'product_id'=>5,                     
                                    'qty'=>$data['kitty'],                     
                                    'date'=>now(),                     
                                ]);
                                $kitty = Product::find(5);
                                $kitty->update(['stock'=> $kitty->stock + $data['kitty']] );
                            }
                            if ($data['dust']) {
                                $this->advProcessing->update(['dust'=>$this->advProcessing->dust + $data['dust']]);
                            }

                            $this->advProcessing->update(['processed'=>$this->advProcessing->processed + $sum]);
                            if ($this->advProcessing->type == 'scrap') {
                                $scrap = $this->advProcessing->scrap;
                                $scrap->update(['stock'=>$scrap->stock - $sum]);
                            }
                            // else{
                            //     $advProduct = $this->advProcessing->product;
                            //     $advProduct->update(['stock'=>$advProduct->stock - $data['quantity']]);
                            // }
                            if ($this->advProcessing->un_processed_quantity == 0) {
                                $this->advProcessing->update(['end_date'=>now()]);
                            }

                            Notification::make()
                            ->title('Processed successfully')
                            ->success()
                            ->send();
                        }),
                        // Action::make('Process Dust')
                        // ->modalWidth(MaxWidth::Small)
                        // ->icon('heroicon-o-funnel')
                        // ->visible(fn () : bool => $this->advProcessing->un_processed_quantity > 0)
                        // ->form([
                        //     TextInput::make('quantity')
                        //     ->suffix('Kg')
                        //     ->rules([
                        //         fn (): Closure => function (string $attribute, $value, Closure $fail ) {
                        //             if ($value > $this->advProcessing->un_processed_quantity ) {
                        //                 $fail('The quantity is invalid.');
                        //             }
                        //         },
                        //     ])
                        //     ->required(),
                        // ])
                        // ->action(function ($data) {
                        //     $this->advProcessing->update(['processed'=>$this->advProcessing->processed + $data['quantity']]);
                        //     $this->advProcessing->update(['dust'=>$this->advProcessing->dust + $data['quantity']]);
                        //     if ($this->advProcessing->un_processed_quantity == 0) {
                        //         $this->advProcessing->update(['end_date'=>now()]);
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
                                ->visible(fn($state)=>$state)
                                ->placeholder('--')
                                ->weight(FontWeight::Black),
                                TextEntry::make('product.name')
                                ->visible(fn($state)=>$state)
                                ->weight(FontWeight::Black),
                                TextEntry::make('purchaseLineItem.purchase.vendor.name')
                                ->label('Vendor')
                                ->weight(FontWeight::Black),
                                TextEntry::make('purchaseLineItem.purchase.id')
                                ->label('Purchase#')
                                ->weight(FontWeight::Black)
                                ->url(fn($state)=>route('purchases.view',$state)),
                                TextEntry::make('purchaseLineItem.purchase.date')
                                ->label('Purchase Date')
                                ->date('d-m-Y')
                                ->weight(FontWeight::Black),
                                TextEntry::make('basicProcessing.id')
                                ->label('Basic Processing#')
                                ->weight(FontWeight::Black)
                                ->visible(fn()=>$this->advProcessing->basicProcessing()->count())
                                ->url(fn($state)=>route('basic-processings.view',$state)),
                                TextEntry::make('qty')
                                ->badge()
                                ->suffix(' Kg')
                                ->label('Total Quantity'),                           
                            ])
                            ->columns(4),
                        Fieldset::make('')
                            ->schema([
                                Grid::make(3)
                                ->schema([
                                    TextEntry::make('processed')
                                    ->badge()
                                    ->suffix(' Kg')
                                    ->label('Processed Quantity'),
                                    TextEntry::make('un_processed_quantity')
                                    ->label('UnProcessed Quantity')
                                    ->badge()
                                    ->color('danger')
                                    ->suffix(' Kg')
                                    ->numeric(decimalPlaces: 3),
                                    TextEntry::make('dust')
                                    ->badge()
                                    ->color('warning')
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
                                    TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string  => match ($state) {                    
                                        'In Progress' => 'warning',
                                        'Processed' => 'success',
                                    }) ,
    
                                ]),
                            ])
                            ->columns(2),
                        ViewEntry::make('product-details')
                        ->label('')
                        ->view('infolists.components.advanced-processing-products-table')
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

    public function render()
    {
        return view('livewire.advancedprocessing-view');
    }
}
