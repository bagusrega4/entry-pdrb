@extends('layouts.app')

@section('title', 'Riwayat Simulasi')

@section('content')

<style>
    * { box-sizing: border-box; }
    body { background: #f7f6f3; }
    .page { padding: 24px 24px 48px; padding-top: 80px; font-family: 'Figtree', sans-serif; }

    .card { background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:16px; }
    .card-head {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 20px; border-bottom:0.5px solid #e5e7eb; background:#fafafa; gap:8px; flex-wrap:wrap;
    }
    .card-head-left { display:flex; align-items:center; gap:8px; }
    .card-head-icon { color:#9ca3af; }
    .card-head-title { font-size:14px; font-weight:500; color:#111827; }

    .btn-back {
        display:inline-flex; align-items:center; gap:6px;
        height:36px; padding:0 16px; font-size:13px; font-weight:500;
        color:#374151; background:#f9fafb; border:0.5px solid #e5e7eb; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; text-decoration:none;
        transition: background 0.12s;
    }
    .btn-back:hover { background:#f3f4f6; text-decoration:none; color:#111827; }

    .riwayat-table { width:100%; border-collapse:collapse; font-size:13px; }
    .riwayat-table thead th {
        font-size:11px; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;
        color:#6b7280; padding:10px 16px; border-bottom:0.5px solid #e5e7eb;
        background:#f9fafb; white-space:nowrap; text-align:left;
    }
    .riwayat-table thead th.th-right { text-align:right; }
    .riwayat-table thead th.th-center { text-align:center; }
    .riwayat-table thead th.th-ntb { background:#f0fdfa; color:#0d9488; text-align:right; }
    .riwayat-table thead th.th-output { background:#f0fdf4; color:#166534; text-align:right; }
    .riwayat-table tbody tr:hover td { background:#fafafa; }
    .riwayat-table tbody tr:not(:last-child) td { border-bottom:0.5px solid #f0f0f0; }
    .riwayat-table tbody td { padding:12px 16px; color:#374151; vertical-align:middle; }
    .riwayat-table tbody td.td-right { text-align:right; }
    .riwayat-table tbody td.td-center { text-align:center; }
    .riwayat-table tbody td.td-ntb { background:#f0fdfa; text-align:right; }
    .td-mono { font-family:monospace; font-size:12px; }

    .tag {
        display:inline-block; font-size:11px; font-weight:500;
        padding:2px 9px; border-radius:100px;
    }
    .tag-orange { background:#fff7ed; color:#c2410c; border:0.5px solid #fed7aa; }
    .tag-green  { background:#f0fdf4; color:#166534; border:0.5px solid #86efac; }
    .tag-blue   { background:#eff6ff; color:#1d4ed8; border:0.5px solid #bfdbfe; }
    .tag-teal   { background:#f0fdfa; color:#0d9488; border:0.5px solid #99f6e4; }

    .badge-output {
        display:inline-flex; align-items:center; font-size:11.5px; font-weight:600;
        color:#166534; background:#f0fdf4; border:0.5px solid #86efac;
        border-radius:100px; padding:2px 10px; white-space:nowrap; font-family:monospace;
    }
    .badge-ntb {
        display:inline-flex; align-items:center; font-size:11.5px; font-weight:600;
        color:#0d9488; background:#f0fdfa; border:0.5px solid #99f6e4;
        border-radius:100px; padding:2px 10px; white-space:nowrap; font-family:monospace;
    }

    .btn-lihat {
        display:inline-flex; align-items:center; gap:4px;
        height:28px; padding:0 10px; font-size:12px; font-weight:500;
        color:#1d4ed8; background:#eff6ff; border:0.5px solid #bfdbfe; border-radius:6px;
        cursor:pointer; text-decoration:none; font-family:'Figtree',sans-serif;
        transition:background 0.12s;
    }
    .btn-lihat:hover { background:#dbeafe; text-decoration:none; }

    .btn-hapus {
        display:inline-flex; align-items:center; gap:4px;
        height:28px; padding:0 10px; font-size:12px; font-weight:500;
        color:#991b1b; background:#fef2f2; border:0.5px solid #fca5a5; border-radius:6px;
        cursor:pointer; font-family:'Figtree',sans-serif;
        transition:background 0.12s;
    }
    .btn-hapus:hover { background:#fee2e2; }

    .empty-state { padding:56px 20px; text-align:center; color:#9ca3af; }
    .empty-state svg { margin-bottom:14px; color:#e5e7eb; }
    .empty-state h6 { font-size:14px; font-weight:600; color:#374151; margin-bottom:4px; }
    .empty-state p  { font-size:13px; margin:0; }

    /* Legenda kolom */
    .col-legend {
        display:flex; align-items:center; gap:12px; flex-wrap:wrap;
        padding:9px 14px; background:#f9fafb; border-top:0.5px solid #e5e7eb;
        font-size:11px; color:#6b7280;
    }
    .col-legend-item { display:flex; align-items:center; gap:5px; }
    .col-legend-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <div>
            <h3 class="fw-bold mb-1" style="font-family:'Figtree',sans-serif;">Riwayat Simulasi</h3>
            <p style="font-size:13px; color:#9ca3af; margin:0;">Daftar simulasi yang telah disimpan.</p>
        </div>
        <a href="{{ route('simulasi.index') }}" class="btn-back">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Simulasi
        </a>
    </div>

    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <span class="card-head-title">Daftar Riwayat</span>
            </div>
            <span style="font-size:11px; padding:2px 8px; background:#f3f4f6; border:0.5px solid #e5e7eb; border-radius:100px; color:#6b7280;">
                {{ $riwayat->count() }} simulasi
            </span>
        </div>

        @if($riwayat->count() > 0)
        <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table class="riwayat-table" style="min-width:860px;">
                <thead>
                    <tr>
                        <th>Nama Simulasi</th>
                        <th>Sektor Diinjeksi</th>
                        <th>Dataset</th>
                        <th class="th-center">Tahun IO</th>
                        <th class="th-output">ΔX Output (M Rp)</th>
                        <th class="th-ntb">ΔNTB (M Rp)</th>
                        <th class="th-right">Multiplier</th>
                        <th>Tanggal</th>
                        <th class="th-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $row)
                    @php $s = $row->summary; @endphp
                    <tr>
                        <td style="font-weight:500; color:#111827;">{{ $row->nama_simulasi }}</td>
                        <td style="max-width:200px;">
                            @if(!empty($s['sektor_injeksi']))
                                <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                    @foreach($s['sektor_injeksi'] as $si)
                                    <span style="display:inline-flex; flex-direction:column; font-size:10px; font-weight:500;
                                        background:#fff7ed; color:#c2410c; border:0.5px solid #fed7aa;
                                        border-radius:6px; padding:3px 7px; line-height:1.4; white-space:nowrap;">
                                        <span>{{ $si['nama'] }}</span>
                                        <span style="color:#9a3412; font-weight:600; font-family:monospace;">
                                            Rp {{ fmod($si['nilai'], 1) == 0
                                                ? number_format($si['nilai'], 0, ',', '.')
                                                : number_format($si['nilai'], 3, ',', '.') }} M
                                        </span>
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#9ca3af; font-size:11px;">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="tag tag-orange">{{ $s['dataset'] ?? '-' }}</span>
                        </td>
                        <td class="td-center">{{ $s['tahun'] ?? '-' }}</td>
                        <!-- ΔX Output -->
                        <td class="td-right">
                            @if(isset($s['tambahan_output']))
                                <span class="badge-output">+{{ number_format($s['tambahan_output'], 3, ',', '.') }}</span>
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <!-- ΔNTB -->
                        <td class="td-ntb">
                            @if(isset($s['tambahan_ntb']))
                                <span class="badge-ntb">+{{ number_format($s['tambahan_ntb'], 3, ',', '.') }}</span>
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <td class="td-right">
                            @php
                                $mult = $s['multiplier_output'] ?? $s['multiplier'] ?? null;
                                $multNtb = $s['multiplier_ntb'] ?? null;
                            @endphp
                            @if($mult !== null)
                                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:2px;">
                                    <span class="tag tag-blue" title="Output Multiplier">{{ number_format($mult, 4) }}× ΔX</span>
                                    @if($multNtb !== null)
                                        <span class="tag tag-teal" title="NTB Multiplier">{{ number_format($multNtb, 4) }}× NTB</span>
                                    @endif
                                </div>
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="color:#9ca3af; font-size:12px; white-space:nowrap;">
                            {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="td-center">
                            <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                <a href="{{ route('simulasi.lihat-riwayat', $row->id) }}" class="btn-lihat">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat
                                </a>
                                <form action="{{ route('simulasi.hapus', $row->id) }}" method="POST" class="form-hapus" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-hapus">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else
        <div class="empty-state">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <h6>Belum Ada Riwayat</h6>
            <p>Jalankan simulasi lalu klik <strong>Simpan ke Riwayat</strong>.</p>
        </div>
        @endif
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
Swal.fire({ icon:'success', title:'Berhasil', text:'{{ session("success") }}', timer:2200, showConfirmButton:false });
@endif
@if(session('error'))
Swal.fire({ icon:'error', title:'Gagal', text:'{{ session("error") }}', confirmButtonColor:'#f97316' });
@endif

document.querySelectorAll('.form-hapus').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus riwayat ini?',
            text: 'Data tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(r => {
            if (r.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection