<?php

namespace App\Filament\Resources\VehiculoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Vehiculo;
use App\Models\Costo;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action as ActionsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostosRelationManager extends RelationManager
{
    protected static string $relationship = 'costos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Costos')
            ->columns([
                TextColumn::make('item.nombre')
                    ->label('Item'),
                TextColumn::make('valor')
                    ->label('Valor del Item'),
                TextColumn::make('descripcion')
                    ->label('Observacion del Item'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ActionsTable::make('Nuevo_Repuesto')->form([

                    Select::make('item_id')
                        ->relationship('item', 'nombre')
                        ->columnSpan(2)
                        ->required()
                        ->label('Repuesto'),
                    TextInput::make('valor')
                        ->columnSpan(3)
                        ->prefix('$ ')
                        ->minValue(0)
                        ->maxValue(9999999999999)
                        ->type('number')
                        ->label('Valor')
                        ->step('1')
                        ->placeholder('0.00'),
                    Textarea::make('descripcion')
                        ->maxLength(255)
                        ->autocomplete(false)
                        ->columnSpan(4)
                        ->label('Observaciones')
                        ->markAsRequired(false),
                    FileUpload::make('ruta_imagen_item')
                        ->label('Imagen del Repuesto')
                        ->columnSpan(4)
                        ->openable()
                        ->downloadable()
                        ->disk('spaces')
                        ->directory('images')
                        ->visibility('public')
                        ->preserveFilenames(),
                ])
                    ->action(fn (array $data, $livewire) => [
                        $vehiculo = Vehiculo::find($this->getOwnerRecord()->id),
                        $costo = Costo::create([
                            'item_id' => $data['item_id'],
                            'valor' => $data['valor'],
                            'descripcion' => $data['descripcion'],
                            'ruta_imagen_item' => $data['ruta_imagen_item'],
                            'vehiculo_id' => $vehiculo->id,
                        ]),
                        $sumatotal = $vehiculo->total_costo + $costo['valor'],
                        $vehiculo->update([
                            'total_costo' => $sumatotal,
                        ]),
                        $livewire->dispatch('refreshForm'),
                    ]),

            ])
            ->actions([
                /* Tables\Actions\EditAction::make(), */

                Action::make('Editar')
                    ->form(function ($record) {
                        return [
                            // Campo oculto para el ID
                            Hidden::make('id')
                                ->default($record->id ?? null),

                            Select::make('item_id')
                                ->relationship('item', 'nombre')
                                ->columnSpan(2)
                                ->required()
                                ->searchable()
                                ->label('Repuesto')
                                ->default($record->item_id ?? null), // Cargar valor existente
                            TextInput::make('valor')
                                ->columnSpan(3)
                                ->prefix('$ ')
                                ->minValue(0)
                                ->maxValue(9999999999999)
                                ->type('number')
                                ->label('Valor')
                                ->step('1')
                                ->placeholder('0.00')
                                ->default($record->valor ?? 0), // Cargar valor existente
                            Textarea::make('descripcion')
                                ->maxLength(255)
                                ->autocomplete(false)
                                ->columnSpan(4)
                                ->label('Observaciones')
                                ->markAsRequired(false)
                                ->default($record->descripcion ?? ''), // Cargar valor existente
                            FileUpload::make('ruta_imagen_item')
                                ->label('Imagen del Repuesto')
                                ->columnSpan(4)
                                ->openable()
                                ->downloadable()
                                ->disk('spaces')
                                ->directory('images')
                                ->visibility('public')
                                ->default($record->ruta_imagen_item ?? null), // Cargar valor existente
                        ];
                    })
                    ->action(function (array $data, $livewire) {
                        $vehiculo = Vehiculo::find($this->getOwnerRecord()->id);

                        // Buscar el costo existente
                        $costo = Costo::find($data['id']);

                        // Calcular la diferencia y ajustar el total del costo del vehículo
                        $valorAnterior = $costo ? $costo->valor : 0;
                        $diferencia = $data['valor'] - $valorAnterior;

                        // Si el costo existe, actualiza, de lo contrario, crea uno nuevo
                        if ($costo) {
                            $costo->update([
                                'item_id' => $data['item_id'],
                                'valor' => $data['valor'],
                                'descripcion' => $data['descripcion'],
                                'ruta_imagen_item' => $data['ruta_imagen_item'],
                            ]);
                        } else {
                            $costo = Costo::create([
                                'item_id' => $data['item_id'],
                                'valor' => $data['valor'],
                                'descripcion' => $data['descripcion'],
                                'ruta_imagen_item' => $data['ruta_imagen_item'],
                                'vehiculo_id' => $vehiculo->id,
                            ]);
                        }

                        $sumatotal = $vehiculo->total_costo + $diferencia;
                        $vehiculo->update([
                            'total_costo' => $sumatotal,
                        ]);

                        $livewire->dispatch('refreshForm');
                    }),

                Action::make('Eliminar Repuesto')
                    ->requiresConfirmation()
                    ->action(
                        function (Costo $record, $livewire) {
                            $vehiculo = $this->getOwnerRecord();
                            $nuevo_valor = $vehiculo->total_costo - $record->valor;
                            $vehiculo->update([
                                'total_costo' => $nuevo_valor,
                            ]);
                            $record->delete();
                            $livewire->dispatch('refreshForm');
                        }
                    ),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                   /*  Tables\Actions\DeleteBulkAction::make(), */
                ]),
            ]);
    }
}
