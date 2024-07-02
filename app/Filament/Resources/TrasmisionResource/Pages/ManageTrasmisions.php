<?php

namespace App\Filament\Resources\TrasmisionResource\Pages;

use App\Filament\Resources\TrasmisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTrasmisions extends ManageRecords
{
    protected static string $resource = TrasmisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
