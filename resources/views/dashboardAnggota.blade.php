@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Anggota</h3>
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
        <div class="row mb-2">
            <div class="col-md">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Total Keluar Dinas Tahun Ini</h6>
                    <h3 class="fw-bold text-primary">{{ $totalDinasTahun }}</h3>
                </div>
            </div>
            <div class="col-md">
                <div class="card shadow-sm rounded-3 text-center p-3">
                    <h6>Total Keluar Dinas Bulan Ini</h6>
                    <h3 class="fw-bold text-success">{{ $totalDinasBulan }}</h3>
                </div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Persentase Keluar Dinas ({{ $selectedYear }})</h6>
                    <div style="height:300px">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Jumlah Keluar Dinas ({{ $selectedYear }})</h6>
                    <div style="height:300px">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Tren Bulanan Keluar Dinas ({{ $selectedYear }})</h6>
                    <div style="height:400px">
                        <canvas id="lineChart"></canvas>
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
</script>
@endsection
