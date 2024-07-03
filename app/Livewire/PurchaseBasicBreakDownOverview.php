<?php

namespace App\Livewire;

use App\Models\BasicProcessing;
use App\Models\BasicProcessingMaterial;
use App\Models\Purchase;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class PurchaseBasicBreakDownOverview extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public Purchase $purchase;
    public array $purchase_line_item_ids = [];
    public array $basic_processing_material_ids = [];

    public function mount($purchase)
    {
        $this->purchase = $purchase;
        $this->purchase_line_item_ids = $this->purchase->lineItems()->pluck('id')->toArray();
        $this->basic_processing_material_ids = BasicProcessing::whereIn('purchase_line_item_id',$this->purchase_line_item_ids)->pluck('id')->toArray();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Basic Processing Report')
            ->striped()
            ->query(BasicProcessingMaterial::query())
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('basic_processing_id', $this->basic_processing_material_ids))
            ->columns([
                TextColumn::make('date')
                    ->weight(FontWeight::Black)
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('product.name')
                    ->weight(FontWeight::Bold)
                    ->color(Color::Blue)
                    ->sortable(),
                TextColumn::make('qty')
                    ->suffix(' Kg')
                    ->summarize(Sum::make('qty'))
                    ->label('Quantity'),
            ])
            ->defaultGroup('product.name')
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }


    public function render()
    {
        return view('livewire.purchase-basic-break-down-overview');
    }
}
