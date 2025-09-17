@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Pimpinan</h3>
                <h6 class="op-7 mb-2">Ringkasan Rencana Kerja & Monitoring</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('monitoring.operator.index') }}" class="btn btn-label-info btn-round me-2">Monitoring</a>
                <a href="{{ route('form.index') }}" class="btn btn-primary btn-round">Tambah Rencana Kerja</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                            @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Total Keluar Dinas Tahun Ini</h6>
                    <h3 class="fw-bold text-primary">{{ $totalDinasTahun }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Total Keluar Dinas Bulan Ini</h6>
                    <h3 class="fw-bold text-success">{{ $totalDinasBulan }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Tim Paling Aktif</h6>
                    <h5 class="fw-bold text-info">{{ $timPalingAktifNama }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Rata-rata Keluar Dinas / Bulan</h6>
                    <h3 class="fw-bold text-warning">{{ $rataRataBulan }}</h3>
                </div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Persentase Keluar Dinas per Tim ({{ $selectedYear }})</h6>
                    <div style="height:300px">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Top Tim dengan Jumlah Keluar Dinas Terbanyak ({{ $selectedYear }})</h6>
                    <div style="height:300px">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Tren Bulanan Keluar Dinas per Tim ({{ $selectedYear }})</h6>
                    <div style="height:400px">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Distribusi Jenis Kegiatan per Tim ({{ $selectedYear }})</h6>
                    <div style="height:400px">
                        <canvas id="stackedBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Matriks Pegawai vs Kegiatan ({{ $selectedYear }})</h6>
                    <div class="mb-3" style="max-width: 300px;">
                        <label for="searchKegiatan" class="form-label fw-bold">Cari Kegiatan</label>
                        <input type="text" id="searchKegiatan" class="form-control" placeholder="Ketik nama kegiatan...">
                    </div>

                    <div class="table-responsive">
                        <table id="kegiatanTable" class="table table-bordered table-striped text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th></th>
                                    @foreach($userLabels as $user)
                                        <th>{{ $user }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatanLabels as $index => $kegiatan)
                                    <tr>
                                        <td class="fw-bold">{{ $kegiatan }}</td>
                                        @foreach($userLabels as $uIndex => $user)
                                            @php
                                                $nilai = collect($matrixData)->firstWhere(fn($d) => $d['x'] == $uIndex && $d['y'] == $index)['v'] ?? 0;
                                            @endphp
                                            <td>{{ $nilai }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.3.0/dist/chartjs-chart-matrix.min.js"></script>

<script>
document.getElementById("searchKegiatan").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#kegiatanTable tbody tr");

    rows.forEach(row => {
        let kegiatan = row.querySelector("td").textContent.toLowerCase();
        if (kegiatan.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<script>
    // --- Pie Chart ---
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($pieLabels) !!},
            datasets: [{
                data: {!! json_encode($pieData) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let dataset = context.dataset;
                            let total = dataset.data.reduce((sum, val) => sum + val, 0);
                            let value = dataset.data[context.dataIndex];
                            let percentage = ((value / total) * 100).toFixed(1) + '%';
                            return percentage;
                        }
                    }
                }
            }
        }
    });

    // --- Bar Chart ---
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($barLabels) !!},
            datasets: [{
                label: 'Jumlah Dinas',
                data: {!! json_encode($barData) !!},
                backgroundColor: '#36b9cc'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // --- Line Chart ---
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($lineLabels) !!},
            datasets: {!! json_encode($lineDatasets) !!}
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // --- Stacked Bar Chart ---
    new Chart(document.getElementById('stackedBarChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($timLabels) !!},
            datasets: {!! json_encode($stackedDatasets) !!}
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: { mode: 'index', intersect: false },
                title: { display: false }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
</script>
@endsection