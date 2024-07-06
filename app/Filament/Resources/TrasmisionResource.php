<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrasmisionResource\Pages;
use App\Filament\Resources\TrasmisionResource\RelationManagers;
use App\Models\Trasmision;
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

class TrasmisionResource extends Resource
{
    protected static ?string $model = Trasmision::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationGroup = 'Parametros Generales';
    protected static ?string $modelLabel = 'Transmision';
    protected static ?string $navigationLabel = 'Trasmisiones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('nombre')
                ->required()
                ->label('Tipo de Trasmision')
                ->unique(ignoreRecord: true)
                ->autocomplete(false)
                ->validationMessages([
                    'unique' => 'El :attribute ya esta registrado.',
                ])
                ->maxLength(40)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nombre')
                ->label('Tipo de Trasmision')
                ->searchable(),
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
            'index' => Pages\ManageTrasmisions::route('/'),
        ];
    }
}
