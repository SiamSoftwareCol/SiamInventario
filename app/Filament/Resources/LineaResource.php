<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LineaResource\Pages;
use App\Filament\Resources\LineaResource\RelationManagers;
use App\Models\Linea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LineaResource extends Resource
{
    protected static ?string $model = Linea::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-trending-up';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Linea';
    protected static ?string $navigationLabel = 'Lineas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('codigo')
                ->required()
                ->autocomplete(false)
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->maxLength(255),
                TextInput::make('nombre')
                ->unique(ignoreRecord: true)
                ->autocomplete(false)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->required()
                ->maxLength(255),
                Textarea::make('descripcion')
                ->minLength(2)
                ->autocomplete(false)
                ->maxLength(1024)
                ->rows(12)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo'),
                Tables\Columns\TextColumn::make('nombre'),
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
            'index' => Pages\ManageLineas::route('/'),
        ];
    }
}
