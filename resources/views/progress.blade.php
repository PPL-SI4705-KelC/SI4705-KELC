<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Act4Climate - Progress Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Progress Tracking Emisi - Act4Climate</h2>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><strong>Tren Emisi Karbon (kg CO2)</strong></div>
            <div class="card-body">
                <canvas id="emissionChart" width="400" height="150"></canvas>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>Riwayat Aktivitas</strong></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Nilai Input</th>
                            <th>Dampak Karbon (kg CO2)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $r)
                        <tr>
                            <td>{{ $r->recorded_at }}</td>
                            <td>{{ $r->activity_type }}</td>
                            <td>{{ $r->amount_value }}</td>
                            <td><strong>{{ $r->carbon_impact }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada data. Gunakan Tinker untuk isi data dummy.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('emissionChart').getContext('2d');
        const emissionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('recorded_at')) !!},
                datasets: [{
                    label: 'Total Emisi',
                    data: {!! json_encode($chartData->pluck('carbon_impact')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>