<?php

namespace App\Livewire;

use App\Models\Vendor;
use Closure;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
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
class VendorList extends Component implements  HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Vendors')
            ->striped()
            ->headerActions([
                CreateAction::make()
                ->form([
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
                ]),
            ])
            ->query(Vendor::query())
            ->columns([
                TextColumn::make('name')
                ->weight(FontWeight::Bold)
                ->color(Color::Blue)
                ->searchable(),
                TextColumn::make('email')
                ->placeholder('--'),
                TextColumn::make('contact_person')
                ->placeholder('--'),
                TextColumn::make('mobile')
                    ->placeholder('--'),
                TextColumn::make('address')
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
                                    if (Vendor::where('name',$value)->where('id','!=', $record->id)->exists()) {
                                        $fail("The name is already taken.");
                                    }
                                },
                            ])
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
                                ->autosize()
                                ->maxLength(255),
                            ]),
                    ]),
                DeleteAction::make()
            ]) 
            // ->recordUrl(
            //     fn (Vendor $record): string => route('vendors.view', $record),
            // )
            ->defaultSort('created_at', 'acs');
    }
    public function render()
    {
        return view('livewire.vendor-list');
    }
}
