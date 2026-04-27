<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Act4Climate - Progress Tracking</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .hover-card { transition: all 0.3s ease; border: none; border-radius: 12px; }
        .hover-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; }
        .table-row-hover:hover { background-color: rgba(16, 185, 129, 0.05) !important; transition: background-color 0.2s; }
        .sdg-box { border-radius: 16px; border: none; transition: transform 0.3s ease; }
        .sdg-box:hover { transform: scale(1.02); }
        .filter-container { background: #ffffff; border-radius: 12px; padding: 15px 25px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 1.5rem; transition: box-shadow 0.3s; }
        .filter-container:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius: 8px; }
        .btn-rounded { border-radius: 8px; font-weight: 500; }
        .table th { border-top: none; color: #6c757d; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Act4Climate Logo" height="50" style="object-fit: contain;">
                <h2 class="fw-bold text-dark m-0">Progress Tracking <span class="text-success">Emisi</span></h2>
            </div>
            <span class="badge bg-success px-3 py-2 rounded-pill fs-6 shadow-sm">Act4Climate</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-4 shadow-sm">{{ session('success') }}</div>
        @endif
        
        <!-- SDG Score -->
        <div class="alert alert-{{ $sdg_color ?? 'success' }} shadow-sm mb-4 sdg-box" style="background-color: var(--bs-{{ $sdg_color ?? 'success' }});">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <h1 class="display-4 mb-0" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">🌍</h1>
                </div>
                <div>
                    <h4 class="alert-heading fw-bold mb-1 text-dark">Kinerja Emisi Anda: <span class="text-uppercase">{{ $sdg_category ?? 'Bagus Sekali! 🌿' }}</span></h4>
                    <p class="mb-0 fs-5 text-dark" style="opacity: 0.9;">{{ $sdg_message ?? 'Emisi Anda sangat terkendali dan ramah lingkungan. Terus pertahankan gaya hidup hijau ini!' }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Bar dipindah ke bawah SDG Announcement -->
        <div class="filter-container">
            <form action="{{ route('progress') }}" method="GET" class="m-0">
                <div class="row align-items-center">
                    <div class="col-md-auto mb-2 mb-md-0 fw-bold text-secondary">
                        🔍 Filter Data:
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <input type="date" name="filter_date" class="form-control form-control-sm" value="{{ request('filter_date') }}" title="Pilih Tanggal">
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select name="filter_activity" class="form-select form-select-sm">
                            <option value="">Semua Aktivitas</option>
                            <option value="Listrik" {{ request('filter_activity') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="Transportasi" {{ request('filter_activity') == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-rounded flex-grow-1">Terapkan</button>
                        <a href="{{ route('progress') }}" class="btn btn-outline-secondary btn-sm btn-rounded flex-grow-1">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="row">
            <!-- Chart Section -->
            <div class="col-lg-12 mb-4">
                <div class="card hover-card shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Tren Emisi Karbon (kg CO2)</h5>
                        <select id="chartTypeSelector" class="form-select form-select-sm shadow-sm" style="width: 160px; border-radius: 8px; cursor: pointer;" onchange="changeChartType(this.value)">
                            <option value="line">📈 Line Chart</option>
                            <option value="bar">📊 Bar Chart</option>
                            <option value="doughnut">🍩 Doughnut Chart</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <canvas id="emissionChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-lg-12">
                <div class="card hover-card shadow-sm mb-5">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2">
                        <h5 class="fw-bold mb-0 text-dark">Riwayat Aktivitas Detail</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Tanggal</th>
                                        <th class="py-3">Aktivitas</th>
                                        <th class="py-3">Nilai Input</th>
                                        <th class="pe-4 py-3 text-end">Dampak Karbon (kg CO2)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($carbon_footprints as $r)
                                    <tr class="table-row-hover">
                                        <td class="ps-4 py-3">
                                            <a href="{{ route('progress', array_merge(request()->query(), ['filter_date' => $r->recorded_at])) }}" class="text-decoration-none text-dark" title="Filter berdasarkan tanggal ini">
                                                <span class="badge bg-white text-dark border shadow-sm">{{ \Carbon\Carbon::parse($r->recorded_at)->format('d M Y') }}</span>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <a href="{{ route('progress', array_merge(request()->query(), ['filter_activity' => $r->activity_type])) }}" class="text-decoration-none fw-bold text-success" title="Filter aktivitas ini">
                                                {{ $r->activity_type }}
                                            </a>
                                        </td>
                                        <td class="py-3 text-secondary fw-medium">{{ $r->amount_value }}</td>
                                        <td class="pe-4 py-3 text-end"><strong class="text-dark fs-6">{{ $r->carbon_impact }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Belum ada data aktivitas untuk filter yang Anda pilih.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($carbon_footprints->hasPages())
                    <div class="card-footer bg-transparent border-0 pb-3 pt-3">
                        {{ $carbon_footprints->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('emissionChart').getContext('2d');
        const chartLabels = {!! json_encode($chartLabels ?? []) !!};
        const chartData = {!! json_encode($chartData ?? []) !!};

        // Create sleek gradient for line chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        let emissionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Emisi (kg CO2)',
                    data: chartData,
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // modern curvy line
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, family: 'Segoe UI' },
                        bodyFont: { size: 15, weight: 'bold', family: 'Segoe UI' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' kg CO2';
                            }
                        }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#e9ecef' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });

        // Function for interactive dropdown chart switching
        function changeChartType(newType) {
            emissionChart.config.type = newType;
            if (newType === 'doughnut') {
                emissionChart.options.scales = { x: {display: false}, y: {display: false} };
                // Stylish colorful palette for doughnut
                emissionChart.data.datasets[0].backgroundColor = [
                    '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'
                ];
                emissionChart.options.plugins.legend.display = true;
                emissionChart.options.plugins.legend.position = 'right';
            } else {
                emissionChart.options.scales = { 
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#e9ecef' }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                };
                emissionChart.data.datasets[0].backgroundColor = newType === 'bar' ? '#10b981' : gradient;
                emissionChart.options.plugins.legend.display = false;
            }
            emissionChart.update();
        }
    </script>
</body>
</html>