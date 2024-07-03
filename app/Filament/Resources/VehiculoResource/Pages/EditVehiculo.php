<?php

namespace App\Filament\Resources\VehiculoResource\Pages;

use Livewire\Attributes\On;
use App\Filament\Resources\VehiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehiculo extends EditRecord
{
    protected static string $resource = VehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    #[On('refreshForm')]
    public function refreshForm(): void
    {
        parent::refreshFormData(array_keys($this->record->toArray()));
    }
}
