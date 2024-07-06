<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FabricanteResource\Pages;
use App\Filament\Resources\FabricanteResource\RelationManagers;
use App\Models\Fabricante;
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

class FabricanteResource extends Resource
{
    protected static ?string $model = Fabricante::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Fabricante';
    protected static ?string $navigationLabel = 'Fabricantes';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
            TextInput::make('nombre')
                ->required()
                ->label('Fabricante')
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->autocomplete(false)
                ->columnSpan(2)
                ->maxLength(15),
            Textarea::make('descripcion')
                ->minLength(2)
                ->maxLength(1024)
                ->rows(4)
                ->autocomplete(false)
                ->label('Descripcion del Fabricante')
                ->required()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nombre')
                ->label('Fabricante')
                ->searchable(),
            TextColumn::make('descripcion')
                ->label('Descripcion')
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
            'index' => Pages\ManageFabricantes::route('/'),
        ];
    }
}
