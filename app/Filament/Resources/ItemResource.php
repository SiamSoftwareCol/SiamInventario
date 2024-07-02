<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Item';
    protected static ?string $navigationLabel = 'Items';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
            TextInput::make('nombre')
                ->required()
                ->label('Nombre Item')
                ->autocomplete(false)
                ->columnSpan(2)
                ->maxLength(15),
            Textarea::make('referencia')
                ->minLength(2)
                ->maxLength(1024)
                ->rows(4)
                ->autocomplete(false)
                ->label('Referencia de Item')
                ->required()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nombre')
                ->label('Item')
                ->searchable(),
            TextColumn::make('referencia')
                ->label('Referencia')
                ->limit(100),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageItems::route('/'),
        ];
    }
}
