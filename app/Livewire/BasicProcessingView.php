<?php

namespace App\Livewire;

use App\Models\BasicProcessing;
use App\Models\BasicProcessingMaterial;
use App\Models\Material;
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
class BasicProcessingView extends Component implements HasForms, HasInfolists , HasTable
{
    use InteractsWithTable;
    use InteractsWithInfolists;
    use InteractsWithForms;
    
    public BasicProcessing $basicProcessing ;

    public function  mount($id)
    {
        $this->basicProcessing = BasicProcessing::find($id);    
    }


    public function productionInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->basicProcessing)
            ->schema([
                Split::make([
                    Section::make('Details')
                    ->headerActions([
                        Action::make('Process')
                            ->modalWidth(MaxWidth::Small)
                            ->icon('heroicon-o-clipboard')
                            ->visible(fn () : bool => $this->basicProcessing->un_processed_quantity > 0)
                            ->form([
                                Select::make('material')
                                ->options(Material::all()->pluck('name','id')),
                                TextInput::make('quantity')
                                ->suffix('Kg')
                                ->rules([
                                    fn (): Closure => function (string $attribute, $value, Closure $fail ) {
                                        if ($value > $this->basicProcessing->un_processed_quantity ) {
                                            $fail('The quantity is invalid.');
                                        }
                                    },
                                ])
                                ->required(),
                            ])
                            ->action(function ($data) {
                                $this->basicProcessing->materials()->create([
                                    'material_id'=>$data['material'],                     
                                    'qty'=>$data['quantity'],                     
                                    'date'=>now(),                     
                                ]);
                                $this->basicProcessing->update(['processed'=>$this->basicProcessing->processed + $data['quantity']]);
                                if ($this->basicProcessing->un_processed_quantity == 0) {
                                    $this->basicProcessing->update(['end_date'=>now()]);
                                }
                                Notification::make()
                                ->title('Processed successfully')
                                ->success()
                                ->send();
                            })
                    ])
                    ->schema([
                        TextEntry::make('scrapProduct.name')
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
                        ViewEntry::make('product-details')
                        ->label('')
                        ->view('infolists.components.basic-processing-materials-table')
                        // Fieldset::make('Processed Details')
                        // ->schema([
                        // ])->columns(1),
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
            ->columns([
                TextColumn::make('')
                ->rowIndex(),
                TextColumn::make('date')
                    ->date('d-m-y')
                    ->weight(FontWeight::Medium)
                    ->size(TextColumnSize::Medium),
                TextColumn::make('material.name')
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
            ->defaultGroup('material.name')
            ->defaultSort('created_at', 'acs');;
    }

    public function render()
    {
        return view('livewire.basic-processing-view');
    }
}
