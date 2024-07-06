<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoResource\Pages;
use App\Filament\Resources\EstadoResource\RelationManagers;
use App\Models\Estado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstadoResource extends Resource
{
    protected static ?string $model = Estado::class;

    protected static ?string $navigationIcon = 'heroicon-c-clipboard-document-check';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Estado';
    protected static ?string $navigationLabel = 'Estados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('codigo')
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->maxLength(3),
                TextInput::make('nombre')
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->maxLength(12),
                Textarea::make('descripcion')
                ->minLength(2)
                ->maxLength(1024)
                ->rows(4)
                ->required()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                ->label('Estado')
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
            'index' => Pages\ManageEstados::route('/'),
        ];
    }
}
