<?php

namespace App\Filament\Resources\LogbookResource\Pages;

use App\Filament\Resources\LogbookResource;
use Filament\Resources\Pages\Page;
use App\Models\Logbook;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PrintLogbook extends Page
{
    protected static string $resource = LogbookResource::class;

    // view Blade yang akan digunakan untuk tampilan
    protected static string $view = 'filament.resources.logbook.print';

    public function mount($record)
    {
        $logbook = Logbook::findOrFail($record);

        $pdf = Pdf::loadView('pdf.logbook', ['logbook' => $logbook]);

        return Response::streamDownload(
            fn () => print($pdf->output()),
            'logbook-' . $logbook->mahasiswa_nim . '.pdf'
        );
    }
}
