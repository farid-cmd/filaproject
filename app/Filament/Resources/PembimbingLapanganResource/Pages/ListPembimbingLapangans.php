<?php

namespace App\Filament\Resources\PembimbingLapanganResource\Pages;

use App\Filament\Resources\PembimbingLapanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembimbingLapangans extends ListRecords
{
    protected static string $resource = PembimbingLapanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
