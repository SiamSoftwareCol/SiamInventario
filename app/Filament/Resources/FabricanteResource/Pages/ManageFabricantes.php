<?php

namespace App\Filament\Resources\FabricanteResource\Pages;

use App\Filament\Resources\FabricanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFabricantes extends ManageRecords
{
    protected static string $resource = FabricanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
