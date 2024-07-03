<?php

namespace App\Livewire;
use App\Models\AdvancedProcessing;
use App\Models\AdvancedProcessingProduct;
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

class PurchaseAdvBreakDownOverview extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public Purchase $purchase;
    public array $purchase_line_item_ids = [];
    public array $adv_processing_material_ids = [];

    public function mount($purchase)
    {
        $this->purchase = $purchase;
        $this->purchase_line_item_ids = $this->purchase->lineItems()->pluck('id')->toArray();
        $this->adv_processing_material_ids = AdvancedProcessing::whereIn('purchase_line_item_id',$this->purchase_line_item_ids)->pluck('id')->toArray();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aluminium Processing Report')
            ->striped()
            ->query(AdvancedProcessingProduct::query())
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('advanced_processing_id', $this->adv_processing_material_ids))
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
            ->paginated(false)
            ->defaultGroup('product.name')
            ->defaultSort('created_at', 'desc');
    }


    public function render()
    {
        return view('livewire.purchase-adv-break-down-overview');
    }
}
