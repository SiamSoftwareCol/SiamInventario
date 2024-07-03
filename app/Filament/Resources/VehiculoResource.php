<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehiculoResource\Pages;
use App\Filament\Resources\VehiculoResource\RelationManagers;
use App\Models\Vehiculo;
use App\Models\Costo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Calculated;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class VehiculoResource extends Resource
{
    protected static ?string $model = Vehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Gestion Vehiculos';
    protected static ?string $modelLabel = 'Vehiculo';
    protected static ?string $navigationLabel = 'Vehiculos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                    ->steps([
                        Wizard\Step::make('Datos Basicos del Vehiculo ')
                            ->columns(9)
                            ->schema([
                                TextInput::make('identificacion')
                                    ->markAsRequired(false)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(16)
                                    ->columnSpan(3)
                                    ->autocomplete(false)
                                    ->prefix('Id')
                                    ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                                    ->label('No. Identificacion'),
                                TextInput::make('motor')
                                    ->markAsRequired(false)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(17)
                                    ->columnSpan(3)
                                    ->autocomplete(false)
                                    ->prefix('Motor')
                                    ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                                    ->label('No. Motor'),
                                TextInput::make('matricula')
                                    ->markAsRequired(false)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(17)
                                    ->columnSpan(3)
                                    ->autocomplete(false)
                                    ->prefix('Matricula')
                                    ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                                    ->label('No. Matricula'),
                                Select::make('fabricante_id')
                                    ->relationship('fabricante', 'nombre')
                                    ->columnSpan(2)
                                    ->required()
                                    ->label('Fabricante'),
                                Select::make('linea_id')
                                    ->relationship('linea', 'nombre')
                                    ->columnSpan(2)
                                    ->required()
                                    ->label('Modelo'),
                                TextInput::make('modelo')
                                    ->markAsRequired(false)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(17)
                                    ->columnSpan(1)
                                    ->autocomplete(false)
                                    ->prefix('Año')
                                    ->rule('regex:/^[0-9]+$/')
                                    ->label('Año'),
                                FileUpload::make('ruta_imagen')
                                    ->label('Imagen')
                                    ->columnSpan(4)
                                    ->openable()
                                    ->deletable(false)
                                    ->downloadable()
                                    ->previewable(true)
                                    ->disk('public')
                                    ->directory('vehiculos')
                                    ->visibility('public'),
                                Select::make('color_id')
                                    ->relationship('color', 'nombre')
                                    ->columnSpan(2)
                                    ->required()
                                    ->label('Color'),
                                Select::make('combustible_id')
                                    ->relationship('combustible', 'nombre')
                                    ->columnSpan(2)
                                    ->required()
                                    ->label('Tipo Combustible'),
                                TextInput::make('capacidad')
                                    ->columnSpan(1)
                                    ->minValue(0)
                                    ->prefix('cc.')
                                    ->maxValue(9999)
                                    ->type('number')
                                    ->label('Capacidad')
                                    ->step('0.01')
                                    ->placeholder('0.00'),
                                Textarea::make('descripcion')
                                    ->maxLength(255)
                                    ->autocomplete(false)
                                    ->columnSpan(4)
                                    ->label('Detalles del Vehiculo')
                                    ->markAsRequired(false),
                                Select::make('trasmision_id')
                                    ->relationship('trasmision', 'nombre')
                                    ->columnSpan(2)
                                    ->required()
                                    ->label('Tipo Trasmision'),
                                TextInput::make('kilometraje')
                                    ->columnSpan(2)
                                    ->minValue(0)
                                    ->prefix('kMS')
                                    ->maxValue(1000000)
                                    ->type('number')
                                    ->label('Kilometraje'),
                            ]),
                        Wizard\Step::make('Datos Comerciales del Vehiculo ')
                            ->columns(10)
                            ->schema([
                                DatePicker::make('fecha_compra')
                                    ->markAsRequired()
                                    ->required()
                                    ->columnSpan(2)
                                    ->label('Fecha de Compra'),
                                TextInput::make('valor_compra')
                                    ->columnSpan(3)
                                    ->minValue(0)
                                    ->prefix('$ |')
                                    ->maxValue(9999999999999)
                                    ->type('number')
                                    ->label('Valor de Compra')
                                    ->step('1')
                                    ->live(onBlur: true)
                                    ->placeholder('0.00'),
                                DatePicker::make('fecha_venta')
                                    ->markAsRequired()
                                    ->required()
                                    ->columnSpan(2)
                                    ->label('Fecha de Venta'),
                                TextInput::make('valor_venta')
                                    ->columnSpan(3)
                                    ->prefix('$ |')
                                    ->minValue(0)
                                    ->live(onBlur: true)
                                    ->maxValue(9999999999999)
                                    ->type('number')
                                    ->label('Valor de Venta')
                                    ->step('1')
                                    ->placeholder('0.00'),
                                TextInput::make('total_costo')
                                    ->columnSpan(4)
                                    ->minValue(0)
                                    ->prefix('$ |')
                                    ->live()
                                    ->disabled()
                                    ->maxValue(9999999999999)
                                    ->type('number')
                                    ->label('Costo Total')
                                    ->step('1')
                                    ->placeholder('0.00'),
                                TextInput::make('utilidad')
                                    ->columnSpan(4)
                                    ->minValue(0)
                                    ->live()
                                    ->prefix('$ |')
                                    ->maxValue(9999999999999)
                                    ->disabled(function (Get $get, Set $set) {
                                        $valor_costo = $get('total_costo');
                                        $valor_compra = $get('valor_compra');
                                        $valor_venta = $get('valor_venta');
                                        $utilidad = $valor_venta - $valor_compra - $valor_costo;
                                        $set('utilidad', $utilidad);
                                        return true;
                                    })
                                    ->type('number')

                                    ->label('Utilidad Total')
                                    ->step('1')
                                    ->placeholder('0.00'),
                                Select::make('estado_id')
                                    ->relationship('estado', 'nombre')
                                    ->columnSpan(3)
                                    ->required()
                                    ->label('Estado Actual'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricula')
                    ->label('No de Matricula')
                    ->searchable(),
                TextColumn::make('estado.nombre')
                    ->label('Estado Vehiculo'),
                TextColumn::make('kilometraje')
                    ->label('Kilometraje'),
                TextColumn::make('fecha_compra')
                    ->label('Fecha de Compra'),
                TextColumn::make('total_costo')
                    ->label('Costos'),
                TextColumn::make('valor_venta')
                    ->label('Valor de Venta'),
                TextColumn::make('utilidad')
                    ->label('Utilidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CostosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehiculos::route('/'),
            'create' => Pages\CreateVehiculo::route('/create'),
            'view' => Pages\ViewVehiculo::route('/{record}'),
            'edit' => Pages\EditVehiculo::route('/{record}/edit'),
        ];
    }
}
