<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class LogbookPrintController extends Controller
{
    public function print($id)
    {
        $logbook = Logbook::findOrFail($id);

        $pdf = Pdf::loadView('pdf.logbook-single', [
            'logbook' => $logbook,
        ]);

        return Response::streamDownload(
            fn () => print($pdf->output()),
            'logbook-' . $logbook->id . '.pdf'
        );
    }
}
