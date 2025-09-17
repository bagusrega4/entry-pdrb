<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Kegiatan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        td.center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Daftar Kegiatan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Periode</th>
                <th>Deskripsi</th>
                <th>Nama Tim</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatanList as $i => $item)
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td>{{ $item->nama_kegiatan }}</td>
                <td>
                    @if($item->periode_mulai && $item->periode_selesai)
                    {{ \Carbon\Carbon::parse($item->periode_mulai)->translatedFormat('d F Y') }}
                    -
                    {{ \Carbon\Carbon::parse($item->periode_selesai)->translatedFormat('d F Y') }}
                    @else
                    Belum diatur
                    @endif
                </td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
                <td>{{ $item->tim->nama_tim ?? '-' }}</td>
                <td class="center">
                    {{ ucfirst($item->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>