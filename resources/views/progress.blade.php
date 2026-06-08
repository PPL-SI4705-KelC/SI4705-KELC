<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Act4Climate - Progress Tracking</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --eco-primary: #059669;
            --eco-accent: #34d399;
            --eco-dark: #064e3b;
            --eco-light: #ecfdf5;
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.4);
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        body { 
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-family: 'DM Sans', sans-serif; 
            min-height: 100vh;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
        }

        /* Organic Background Blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            z-index: -1;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s infinite ease-in-out alternate;
        }
        body::before {
            top: -10%; left: -10%;
            width: 500px; height: 500px;
            background: rgba(16, 185, 129, 0.3);
        }
        body::after {
            bottom: -10%; right: -10%;
            width: 600px; height: 600px;
            background: rgba(52, 211, 153, 0.2);
            animation-delay: -10s;
        }

        h1, h2, h3, h4, h5, .fw-bold { font-family: 'Outfit', sans-serif; }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.1);
        }

        .sdg-hero {
            border-radius: 24px;
            border: none;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            animation: fadeUp 0.8s ease-out forwards;
        }
        
        .sdg-success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .sdg-warning { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        .sdg-danger { background: linear-gradient(135deg, #be123c 0%, #ef4444 100%); }

        .sdg-hero::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><filter id="noiseFilter"><feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/></filter><rect width="100%" height="100%" filter="url(%23noiseFilter)"/></svg>');
            opacity: 0.15;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        .badge-organic {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.4);
            color: white !important;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 30px;
            white-space: nowrap;
        }

        .filter-container {
            border-radius: 16px;
            padding: 18px 25px;
            margin-bottom: 2rem;
            animation: fadeUp 0.8s ease-out 0.2s forwards;
            opacity: 0;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
            background: rgba(255,255,255,0.8);
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            border-color: var(--eco-accent);
            background: #fff;
        }

        .btn-primary {
            background: var(--eco-primary);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: var(--eco-dark);
            transform: scale(1.05);
        }
        
        .btn-outline-secondary {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
            background: white;
            font-weight: 600;
            color: #475569;
        }
        .btn-outline-secondary:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .table-row-hover { transition: all 0.3s ease; }
        .table-row-hover:hover { 
            background-color: var(--eco-light) !important; 
            transform: translateX(5px);
        }
        
        .table th { border-top: none; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        
        .anim-delay-1 { animation: fadeUp 0.8s ease-out 0.4s forwards; opacity: 0; }
        .anim-delay-2 { animation: fadeUp 0.8s ease-out 0.6s forwards; opacity: 0; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 30px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 anim-delay-1" style="animation-delay: 0s;">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Act4Climate" height="50" style="object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                <h2 class="fw-bold m-0" style="color: var(--eco-dark);">Progress Tracking</h2>
            </div>
            <span class="badge" style="background: var(--eco-light); color: var(--eco-primary); border: 1px solid var(--eco-accent); padding: 8px 16px; border-radius: 30px; font-weight: 700;">Act4Climate</span>
        </div>

        @if(session('success'))
            <div class="alert glass-card text-success fw-bold shadow-sm mb-4" style="border-left: 4px solid var(--eco-primary);">{{ session('success') }}</div>
        @endif
        
        <!-- SDG Score Hero Banner -->
        <div class="sdg-hero sdg-{{ $sdg_color ?? 'success' }} p-4 p-md-5 mb-5 d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="display-1" style="filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3)); font-size: 5rem; line-height: 1;">🌍</div>
            <div class="flex-grow-1 text-center text-md-start z-1">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start mb-2 gap-3">
                    <h3 class="fw-bold m-0 text-white" style="letter-spacing: -0.5px;">Kinerja Emisi: <span class="text-uppercase" style="opacity: 0.9;">{{ $sdg_category ?? 'Bagus Sekali! 🌿' }}</span></h3>
                    <span class="badge-organic ms-md-auto">{{ $evalText ?? 'Evaluasi Harian' }}</span>
                </div>
                <p class="mb-0 fs-5 text-white" style="opacity: 0.85; font-weight: 500; max-width: 800px;">{{ $sdg_message ?? 'Emisi Anda sangat terkendali dan ramah lingkungan. Terus pertahankan gaya hidup hijau ini!' }}</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="glass-card filter-container">
            <form action="{{ route('emissions.index') }}" method="GET" class="m-0">
                <div class="row align-items-center g-3">
                    <div class="col-md-auto fw-bold" style="color: var(--eco-dark);">
                        <span class="me-2">🔍</span>Filter
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date') }}" title="Pilih Tanggal">
                    </div>
                    <div class="col-md-3">
                        <select name="filter_activity" class="form-select">
                            <option value="">Semua Aktivitas</option>
                            <option value="Listrik" {{ request('filter_activity') == 'Listrik' ? 'selected' : '' }}>⚡ Listrik</option>
                            <option value="Transportasi" {{ request('filter_activity') == 'Transportasi' ? 'selected' : '' }}>🚗 Transportasi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="chart_range" class="form-select">
                            <option value="7" {{ request('chart_range', 7) == 7 ? 'selected' : '' }}>📈 Tren 7 Hari</option>
                            <option value="1" {{ request('chart_range') == 1 ? 'selected' : '' }}>🎯 Hanya 1 Hari</option>
                        </select>
                    </div>
                    <div class="col-md d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Terapkan</button>
                        <a href="{{ route('emissions.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="row g-5">
            <!-- Chart Section -->
            <div class="col-lg-12 anim-delay-1">
                <div class="glass-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0" style="color: var(--eco-dark);">Tren Karbon</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary fw-bold" style="font-size: 0.85rem;">Tampilan:</span>
                            <select id="chartTypeSelector" class="form-select form-select-sm" style="width: 160px; background-color: #ffffff; border: 2px solid var(--eco-accent); color: var(--eco-dark); font-weight: 600; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.05);" onchange="changeChartType(this.value)">
                                <option value="line">📈 Line Chart</option>
                                <option value="bar">📊 Bar Chart</option>
                                <option value="doughnut">🍩 Doughnut</option>
                            </select>
                        </div>
                    </div>
                    <div style="position: relative; height: 350px; width: 100%;">
                        <canvas id="emissionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-lg-12 anim-delay-2 mb-5">
                <div class="glass-card p-4">
                    <h4 class="fw-bold mb-4" style="color: var(--eco-dark);">Riwayat Detail</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle m-0">
                            <thead>
                                <tr style="border-bottom: 2px solid rgba(0,0,0,0.05);">
                                    <th class="ps-3 py-3">Tanggal</th>
                                    <th class="py-3">Aktivitas</th>
                                    <th class="py-3">Nilai Input</th>
                                    <th class="pe-3 py-3 text-end">Dampak (kg CO2)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carbon_footprints as $r)
                                <tr class="table-row-hover border-bottom" style="border-bottom-color: rgba(0,0,0,0.03) !important;">
                                    <td class="ps-3 py-3">
                                        <a href="{{ route('emissions.index', array_merge(request()->query(), ['filter_date' => $r->recorded_at])) }}" class="text-decoration-none">
                                            <span class="badge" style="background: white; color: #475569; border: 1px solid #cbd5e1; font-family: 'DM Sans'; padding: 6px 12px; font-weight: 500;">
                                                {{ \Carbon\Carbon::parse($r->recorded_at)->format('d M Y') }}
                                            </span>
                                        </a>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('emissions.index', array_merge(request()->query(), ['filter_activity' => $r->activity_type])) }}" class="text-decoration-none fw-bold" style="color: var(--eco-primary);">
                                            {{ $r->activity_type == 'Listrik' ? '⚡' : '🚗' }} {{ $r->activity_type }}
                                        </a>
                                    </td>
                                    <td class="py-3 text-secondary">{{ $r->amount_value }}</td>
                                    <td class="pe-3 py-3 text-end"><strong class="fs-5" style="color: var(--eco-dark);">{{ $r->carbon_impact }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted" style="font-style: italic;">Belum ada jejak karbon untuk filter ini. Saatnya beraksi untuk bumi! 🌱</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($carbon_footprints->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
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

        // Create vibrant eco gradient for line chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        Chart.defaults.font.family = "'DM Sans', sans-serif";
        Chart.defaults.color = "#64748b";

        let emissionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Emisi (kg CO2)',
                    data: chartData,
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#059669',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.45 // organic curvy line
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 16,
                        titleFont: { size: 14, family: 'Outfit', weight: '600' },
                        bodyFont: { size: 16, family: 'DM Sans', weight: '700' },
                        cornerRadius: 12,
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
                        grid: { borderDash: [6, 6], color: 'rgba(0,0,0,0.06)' },
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
                // Organic bright palette
                emissionChart.data.datasets[0].backgroundColor = [
                    '#059669', '#10b981', '#34d399', '#6ee7b7', '#f59e0b', '#fbbf24', '#0f172a'
                ];
                emissionChart.options.plugins.legend.display = true;
                emissionChart.options.plugins.legend.position = 'right';
            } else {
                emissionChart.options.scales = { 
                    y: { beginAtZero: true, grid: { borderDash: [6, 6], color: 'rgba(0,0,0,0.06)' }, border: { display: false } },
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