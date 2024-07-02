<?php

namespace App\Filament\Resources\EstadoResource\Pages;

use App\Filament\Resources\EstadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEstados extends ManageRecords
{
    protected static string $resource = EstadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
