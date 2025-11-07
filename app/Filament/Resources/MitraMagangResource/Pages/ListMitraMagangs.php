<?php

namespace App\Filament\Resources\MitraMagangResource\Pages;

use App\Filament\Resources\MitraMagangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMitraMagangs extends ListRecords
{
    protected static string $resource = MitraMagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
