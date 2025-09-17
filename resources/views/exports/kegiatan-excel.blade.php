<table>
    <thead>
        <tr>
            <th style="font-weight: bold; text-align:center;">No</th>
            <th style="font-weight: bold;">Nama Kegiatan</th>
            <th style="font-weight: bold;">Periode</th>
            <th style="font-weight: bold;">Deskripsi</th>
            <th style="font-weight: bold;">Nama Tim</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kegiatanList as $i => $item)
        <tr>
            <td style="text-align:center;">{{ $i+1 }}</td>
            <td>{{ $item->nama_kegiatan }}</td>
            <td>
                @if($item->periode_mulai && $item->periode_selesai)
                {{ \Carbon\Carbon::parse($item->periode_mulai)->format('d/m/Y') }}
                -
                {{ \Carbon\Carbon::parse($item->periode_selesai)->format('d/m/Y') }}
                @else
                Belum diatur
                @endif
            </td>
            <td>{{ $item->deskripsi ?? '-' }}</td>
            <td>{{ $item->tim->nama_tim ?? '-' }}</td>
            <td>{{ ucfirst($item->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>