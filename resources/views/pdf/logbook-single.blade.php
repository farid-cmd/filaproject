<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logbook {{ $logbook->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Logbook Mahasiswa</h2>

    <p><strong>ID Logbook:</strong> {{ $logbook->id }}</p>
    <p><strong>Nama Mahasiswa:</strong> {{ $logbook->mahasiswa->nama ?? '-' }}</p>
    <p><strong>Tanggal:</strong> {{ $logbook->tanggal }}</p>

    <table>
        <tr>
            <th>Kegiatan</th>
            <td>{{ $logbook->kegiatan }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ $logbook->deskripsi }}</td>
        </tr>
        <tr>
            <th>Dosen Pembimbing</th>
            <td>{{ $logbook->dosen->nama ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
