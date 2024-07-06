<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CombustibleResource\Pages;
use App\Filament\Resources\CombustibleResource\RelationManagers;
use App\Models\Combustible;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CombustibleResource extends Resource
{
    protected static ?string $model = Combustible::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Combustible';
    protected static ?string $navigationLabel = 'Combustibles';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
            TextInput::make('nombre')
                ->required()
                ->label('Tipo de Combustible')
                ->unique(ignoreRecord: true)
                ->autocomplete(false)
                ->columnSpan(4)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('nombre'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageCombustibles::route('/'),
        ];
    }
}
