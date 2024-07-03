<?php

namespace App\Livewire;

use App\Models\AdvancedProcessing;
use App\Models\BasicProcessing;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Models\Purchase;
use App\Models\PurchaseLineItem;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class PurchaseView extends Component implements  HasForms, HasInfolists , HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;
  
    public Purchase $purchase;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->purchase)
            ->schema([
                Section::make('Product Purchase #'.$this->purchase->id)
                ->description('Date: '.date('d-m-Y', strtotime($this->purchase->date)))
                ->schema([
                    Fieldset::make('')
                        ->schema([
                            TextEntry::make('vendor.name')
                            ->weight(FontWeight::ExtraBold)
                            ->size(TextEntrySize::Large)
                            ->fontFamily(FontFamily::Mono)
                            ->inlineLabel(),
                            TextEntry::make('ref')
                            ->weight(FontWeight::ExtraBold)
                            ->size(TextEntrySize::Medium)
                            ->label('Reference')
                            ->placeholder('--')
                            ->inlineLabel(),
                        ])
                        ->columns(2),
                        ViewEntry::make('product-details')
                            ->label('')
                            ->view('infolists.components.product-purchase-table-list')
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->striped()
            ->query(PurchaseLineItem::query()->where('purchase_id',$this->purchase->id))
            ->columns([
                TextColumn::make('')
                ->rowIndex(),
                TextColumn::make('scrap.name')
                    ->weight(FontWeight::Bold)
                    ->size(TextColumnSize::Medium),
                TextColumn::make('qty')
                    ->numeric()
                    ->suffix(' Kg')
                    ->weight(FontWeight::Bold)
                    ->size(TextColumnSize::Medium),
            ])
            ->actions([
                ActionsAction::make('Start Basic Process')
                ->button()
                ->visible(fn($record)=>$record->scrap->is_base && $record->basicProcessings->count() == 0)
                ->action(function($record){
                    $basic = BasicProcessing::create([
                        'scrap_id'=>$record->scrap->id,
                        'qty'=>$record->qty,
                        'purchase_line_item_id'=>$record->id,
                        'start_date'=>now()
                    ]);
                    $this->redirectRoute('basic-processings.view',['id'=>$basic->id]);
                }),
                ActionsAction::make('View Basic Process')
                ->label(function($record){
                    return $record->basicProcessings->first()->end_date == null ? 'Basic Processing In Progress' : 'Basic Processed';
                })
                ->button()
                ->visible(fn($record)=>$record->scrap->is_base && $record->basicProcessings->count() > 0)
                ->url(fn($record)=>route('basic-processings.view',['id'=>$record->basicProcessings->first()->id])),
                ActionsAction::make('Start Aluminium Process')
                ->color('warning')
                ->button()
                ->visible(fn($record)=>!$record->scrap->is_base && $record->advancedProcessings->count() == 0)
                ->action(function($record){
                    $adv = AdvancedProcessing::create([
                        'scrap_id'=>$record->scrap->id,
                        'qty'=>$record->qty,
                        'purchase_line_item_id'=>$record->id,
                        'start_date'=>now()
                    ]);
                    $this->redirectRoute('advanced-processings.view',['id'=>$adv->id]);
                }),
                ActionsAction::make('View Aluminium Process')
                ->button()
                ->label(function($record){
                    return $record->advancedProcessings->first()->end_date == null ? 'Aluminium Processing In Progress' : 'Aluminium Processed';
                })
                ->color('warning')
                ->visible(fn($record)=>$record->advancedProcessings->count() > 0)
                ->url(fn($record)=>route('advanced-processings.view',['id'=>$record->advancedProcessings->first()->id])),
            ])
            ->paginated(false)
            ->defaultSort('created_at', 'acs');;
    }

    public function render()
    {
        return view('livewire.purchase-view');
    }
}
