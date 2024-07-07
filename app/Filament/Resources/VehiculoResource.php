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
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\IconPosition;
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
                    Wizard\Step::make('Datos Básicos del Vehículo')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('identificacion')
                                        ->markAsRequired(false)
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->validationMessages([
                                            'unique' => 'El :attribute ya está registrado.',
                                        ])
                                        ->maxLength(17)
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
                                        ->autocomplete(false)
                                        ->prefix('VIN')
                                        ->disabled(fn($record) => optional($record)->exists ?? false)
                                        ->label('No. Identificación'),
                                    TextInput::make('motor')
                                        ->markAsRequired(false)
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->validationMessages([
                                            'unique' => 'El :attribute ya está registrado.',
                                        ])
                                        ->maxLength(17)
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
                                        ->autocomplete(false)
                                        ->prefix('Motor')
                                        ->disabled(fn($record) => optional($record)->exists ?? false)
                                        ->label('No. Motor'),
                                    TextInput::make('matricula')
                                        ->markAsRequired(false)
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->validationMessages([
                                            'unique' => 'El :attribute ya está registrado.',
                                        ])
                                        ->maxLength(16)
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
                                        ->autocomplete(false)
                                        ->prefix('Matrícula')
                                        ->disabled(fn($record) => optional($record)->exists ?? false)
                                        ->label('No. Matrícula'),
                                    Select::make('fabricante_id')
                                        ->relationship('fabricante', 'nombre')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Fabricante'),
                                    Select::make('linea_id')
                                        ->relationship('linea', 'nombre')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Modelo'),
                                    TextInput::make('modelo')
                                        ->markAsRequired(false)
                                        ->required()
                                        ->maxLength(4)
                                        ->columnSpan(['lg' => 1, 'md' => 6, 'sm' => 12])
                                        ->autocomplete(false)
                                        ->prefix('Año')
                                        ->rule('regex:/^[0-9]+$/')
                                        ->label('Año'),
                                    TextInput::make('kilometraje')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->minValue(0)
                                        ->prefix('Kms')
                                        ->maxValue(1000000)
                                        ->numeric()
                                        ->inputMode('numeric')
                                        ->rules(['numeric', 'min:0.1'])
                                        ->label('Kilometraje'),
                                    Select::make('color_id')
                                        ->relationship('color', 'nombre')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Color'),
                                    Select::make('combustible_id')
                                        ->relationship('combustible', 'nombre')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Tipo Combustible'),
                                    TextInput::make('capacidad')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->prefix('Centímetros Cub.')
                                        ->maxValue(9999)
                                        ->numeric()
                                        ->inputMode('numeric')
                                        ->label('Capacidad')
                                        ->rules(['numeric', 'min:0.1'])
                                        ->step('0.1')
                                        ->placeholder('0.00'),
                                    Select::make('trasmision_id')
                                        ->relationship('trasmision', 'nombre')
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Tipo Transmisión'),
                                    FileUpload::make('ruta_imagen')
                                        ->label('Imagen')
                                        ->columnSpan(['lg' => 3, 'md' => 12, 'sm' => 12])
                                        ->openable()
                                        ->deletable(false)
                                        ->downloadable()
                                        ->previewable(true)
                                        ->disk('spaces')
                                        ->directory('images')
                                        ->visibility('public')
                                        ->preserveFilenames(),
                                    Textarea::make('descripcion')
                                        ->maxLength(255)
                                        ->autocomplete(false)
                                        ->columnSpan(['lg' => 3, 'md' => 12, 'sm' => 12])
                                        ->label('Detalles del Vehículo')
                                        ->markAsRequired(false),
                                ]),
                        ]),
                    Wizard\Step::make('Datos Comerciales del Vehículo')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    DatePicker::make('fecha_compra')
                                        ->markAsRequired()
                                        ->required()
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->label('Fecha de Compra'),
                                    TextInput::make('valor_compra')
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
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
                                        ->columnSpan(['lg' => 2, 'md' => 6, 'sm' => 12])
                                        ->label('Fecha de Venta'),
                                    TextInput::make('valor_venta')
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
                                        ->prefix('$ |')
                                        ->minValue(0)
                                        ->live(onBlur: true)
                                        ->maxValue(9999999999999)
                                        ->type('number')
                                        ->label('Valor de Venta')
                                        ->step('1')
                                        ->placeholder('0.00'),
                                    TextInput::make('total_costo')
                                        ->columnSpan(['lg' => 4, 'md' => 6, 'sm' => 12])
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
                                        ->columnSpan(['lg' => 4, 'md' => 6, 'sm' => 12])
                                        ->minValue(0)
                                        ->live()
                                        ->prefix('$ |')
                                        ->maxValue(9999999999999)
                                        ->readOnly(function (Get $get, Set $set) {
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
                                        ->columnSpan(['lg' => 3, 'md' => 6, 'sm' => 12])
                                        ->required()
                                        ->label('Estado Actual'),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ColumnGroup::make(
                    'Datos Basicos Vehiculo',
                    [
                        TextColumn::make('matricula')
                            ->label('No de Matricula')
                            ->sortable()
                            ->iconPosition(IconPosition::Before)
                            ->weight(FontWeight::Black)
                            ->iconColor('black')
                            ->icon('heroicon-m-truck')
                            ->tooltip('Placa del Vehiculo')
                            ->searchable(),
                        TextColumn::make('modelo')
                            ->label('Modelo')
                            ->sortable()
                            ->toggleable()
                            ->searchable(),
                        TextColumn::make('estado.nombre')
                            ->sortable()
                            ->toggleable()
                            ->label('Estado'),
                        TextColumn::make('kilometraje')
                            ->toggleable()
                            ->sortable()
                            ->numeric(decimalPlaces: 0)
                            ->alignment(Alignment::Center)
                            ->label('Km'),
                        TextColumn::make('fecha_compra')
                            ->sortable()
                            ->alignment(Alignment::Center)
                            ->toggleable()
                            ->label('Fecha Compra'),
                    ]
                ),

                ColumnGroup::make(
                    'Datos Comerciales',
                    [
                        TextColumn::make('valor_compra')
                            ->prefix('$')
                            ->default('0')
                            ->numeric(decimalPlaces: 0)
                            ->tooltip('Precio de Compra del Vehiculo')
                            ->alignment(Alignment::End)
                            ->label('Valor Compra'),
                        TextColumn::make('total_costo')
                            ->prefix('$')
                            ->default('0')
                            ->numeric(decimalPlaces: 0)
                            ->alignment(Alignment::End)
                            ->tooltip('Valor Repuestos Incorporados al Vehiculo')
                            ->label('Total  Costos '),
                        TextColumn::make('valor_venta')
                            ->prefix('$')
                            ->default('0')
                            ->numeric(decimalPlaces: 0)
                            ->alignment(Alignment::End)
                            ->tooltip('Precio de Venta del Vehiculo')
                            ->label('Valor Venta'),
                        TextColumn::make('utilidad')
                            ->tooltip('Margen de Ganacia')
                            ->prefix('$')
                            ->weight(FontWeight::Black)
                            ->numeric(decimalPlaces: 0)
                            ->alignment(Alignment::End)
                            ->default('0')
                            ->label('Utilidad General'),
                    ]
                ),
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
