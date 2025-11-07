<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Logbook Bulk PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Daftar Logbook</h2>
    <table>
        <thead>
            <tr>
                <th>Noo</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                {{-- <th>Keterangan</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($logbooks as $i => $logbook)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $logbook->tanggal }}</td>
                    <td>{{ $logbook->kegiatan }}</td>
                    {{-- <td>{{ $logbook->keterangan ?? '-' }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
