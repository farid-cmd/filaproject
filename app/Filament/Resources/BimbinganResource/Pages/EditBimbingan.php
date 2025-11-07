<?php

namespace App\Filament\Resources\BimbinganResource\Pages;

use App\Filament\Resources\BimbinganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBimbingan extends EditRecord
{
    protected static string $resource = BimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
