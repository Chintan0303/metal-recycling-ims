<?php

namespace App\Livewire;

use App\Models\Scrap;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ScrapProductsList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Scrap Products')
            ->striped()
            ->headerActions([
                CreateAction::make()
                ->form([
                    Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->unique(Scrap::class)
                            ->required()
                            ->maxLength(30),
                        Hidden::make('stock')
                            ->default(0),                                            
                    ]),
                ]),
            ])
            ->query(Scrap::query())
            ->columns([
                TextColumn::make('name')
                ->weight(FontWeight::Bold)
                ->color(Color::Blue)
                ->searchable(),
                TextColumn::make('stock')
                ->suffix(' Kg')
                ->placeholder('--'),
            ])
            ->actions([
                EditAction::make()
                ->form([
                    Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                        ->rules([
                                fn (Model $record ): Closure => function (string $attribute, $value, Closure $fail) use ($record) {
                                    if (Scrap::where('name',$value)->where('id','!=', $record->id)->exists()) {
                                        $fail("The name is already taken.");
                                    }
                                },
                            ])
                            ->required()
                            ->maxLength(30),
                        // TextInput::make('stock')
                        //     ->numeric()
                        //     ->minValue(0)
                        //     ->required(),                                            
                    ]),
                ]),
            ]) 
            ->defaultSort('created_at', 'acs');
    }

    public function render()
    {
        return view('livewire.scrap-products-list');
    }
}
