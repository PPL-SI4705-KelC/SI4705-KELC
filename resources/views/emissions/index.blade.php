<x-app-layout>
    <div class="w-full space-y-6 animate-fade-in pb-10 pt-6">

        {{-- Top Section: Banner + Quiz --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Emission Performance Banner (Takes 2 columns on lg) --}}
            @php
                $bannerBg = match($perfStatus) {
                    'low' => 'from-emerald-500 to-emerald-700',
                    'medium' => 'from-yellow-500 to-yellow-600',
                    'high' => 'from-red-500 to-red-600',
                    default => 'from-gray-400 to-gray-500',
                };
                $evalStart = \Carbon\Carbon::parse($sevenDayStart)->format('d M');
                $evalEnd = \Carbon\Carbon::parse($sevenDayEnd)->format('d M Y');
            @endphp
            <div class="lg:col-span-2 relative rounded-3xl bg-gradient-to-br {{ $bannerBg }} p-10 text-white overflow-hidden shadow-lg flex flex-col justify-center min-h-[200px]">
                <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-8">
                    <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center shrink-0 shadow-lg backdrop-blur-sm border border-white/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-14 h-14 object-contain drop-shadow-lg">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-3">
                            <h3 class="text-3xl font-extrabold tracking-tight drop-shadow-sm leading-tight">{{ $perfLabel }}</h3>
                            <span class="bg-white/20 text-white text-[11px] font-bold px-4 py-2 rounded-full tracking-wider uppercase whitespace-nowrap border border-white/10 backdrop-blur-sm">
                                7 Days: {{ $evalStart }} - {{ $evalEnd }}
                            </span>
                        </div>
                        <p class="text-white/90 text-base leading-relaxed">{{ $perfMessage }}</p>
                    </div>
                    @if($perfStatus !== 'no_data')
                    <div class="mt-4 md:mt-0 text-center shrink-0 bg-white/10 px-8 py-5 rounded-2xl border border-white/20 backdrop-blur-sm min-w-[140px]">
                        <p class="text-[11px] text-white/80 font-bold uppercase tracking-wider mb-2">SDG Score</p>
                        <p class="text-4xl font-black drop-shadow-md leading-none">{{ $avgSdg }}<span class="text-base font-bold text-white/60 ml-1">/100</span></p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Daily Climate Quiz Card (Takes 1 column on lg) --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center text-center relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#2A5C4D]/5 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                <div class="relative z-10">
                    <h3 class="text-[#2A5C4D] font-bold text-xs uppercase tracking-widest mb-3">Daily Quiz</h3>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-4 leading-snug">Track Your<br>Footprint</h2>
                    
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">
                        Every small action counts. Update your daily activities to see real impact.
                    </p>
                    
                    <div class="mt-auto">
                        <a href="{{ route('quiz.index') }}" class="inline-flex w-full items-center justify-center gap-2 bg-[#2A5C4D] hover:bg-[#1e4438] text-white text-sm font-bold py-3.5 px-6 rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                            Start Quiz
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Carbon Trend Chart --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8" x-data="carbonChart()">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-[#2A5C4D]">Carbon Trend</h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 font-medium">View:</span>
                    <select x-model="chartType" @change="updateChartType()" class="bg-[#f0faf5] text-[#2A5C4D] px-3 py-1.5 rounded-lg text-xs font-bold border border-[#2A5C4D]/10 focus:ring-0 focus:border-[#2A5C4D]/30 cursor-pointer outline-none">
                        <option value="line">Line Chart</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                </div>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="carbonTrendChart"></canvas>
            </div>
        </div>

        {{-- Emission History Table --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-[#2A5C4D]">Emission History</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-4 font-bold">Activity Name</th>
                            <th class="py-4 px-4 font-bold">Category</th>
                            <th class="py-4 px-4 font-bold">Input Date</th>
                            <th class="py-4 px-4 font-bold text-right">Total CO₂ Reduced</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($paginatedRows as $row)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-4">
                                <span class="font-bold text-gray-900">{{ $row['name'] }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: {{ $row['dot_color'] }}">
                                    {{ $row['category'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-md bg-gray-50 border border-gray-200 text-xs font-semibold text-gray-600">
                                    {{ $row['date'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-extrabold text-[#2A5C4D] text-right text-base">
                                {{ number_format($row['carbon'], 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium">No emission data recorded yet.</p>
                                    <p class="text-xs">Start by using the Climate Impact Calculator to log your footprint!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($paginatedRows->hasPages())
            <div class="mt-6 flex items-center justify-center gap-2">
                <span class="text-xs text-gray-500">
                    Showing {{ $paginatedRows->firstItem() }} to {{ $paginatedRows->lastItem() }} of {{ $paginatedRows->total() }} results
                </span>
                {{ $paginatedRows->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carbonChart', () => ({
                chartType: 'line',
                chartInstance: null,
                
                init() {
                    // Ensure DOM is ready before rendering
                    setTimeout(() => {
                        this.renderChart();
                    }, 50);
                },

                updateChartType() {
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }
                    this.renderChart();
                },

                renderChart() {
                    const chartData = @json($chartData);

                    if (chartData.length === 0) {
                        document.getElementById('carbonTrendChart').parentElement.innerHTML = 
                            '<div class="flex items-center justify-center h-full text-gray-400 text-sm font-medium">No emission data to display yet. Log your first carbon footprint!</div>';
                        return;
                    }

<<<<<<< HEAD
                    // Format dates to clean 'dd MMM' format (e.g. '29 Apr')
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const labels = chartData.map(d => {
                        const date = new Date(d.emission_date);
                        const day = date.getDate();
                        const month = monthNames[date.getMonth()];
                        return day + ' ' + month;
                    });
=======
                    const labels = chartData.map(d => d.emission_date);
>>>>>>> 23cf5aa1bc2c2abe1c6339f71e906666f4fde41d
                    const totals = chartData.map(d => parseFloat(d.total_emission));

                    const ctx = document.getElementById('carbonTrendChart').getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, 320);
                    gradient.addColorStop(0, 'rgba(42, 92, 77, 0.25)');
                    gradient.addColorStop(1, 'rgba(42, 92, 77, 0.02)');

                    let config = {
                        type: this.chartType === 'pie' ? 'doughnut' : this.chartType,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total CO₂ (kg)',
                                data: totals,
                                borderColor: this.chartType === 'line' ? '#2A5C4D' : 'transparent',
                                backgroundColor: this.chartType === 'line' ? gradient : '#2A5C4D',
                                borderWidth: this.chartType === 'line' ? 2.5 : 0,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#2A5C4D',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                borderRadius: this.chartType === 'bar' ? 4 : 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { 
                                    display: this.chartType === 'pie',
                                    position: 'bottom',
                                    labels: { padding: 20, font: { size: 12 } }
                                },
                                tooltip: {
                                    backgroundColor: '#1B4332',
                                    titleFont: { weight: 'bold', size: 13 },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 10,
                                    displayColors: this.chartType === 'pie',
                                    callbacks: {
<<<<<<< HEAD
                                        title: function(tooltipItems) {
                                            // Show full date in tooltip title
                                            const idx = tooltipItems[0].dataIndex;
                                            const raw = chartData[idx].emission_date;
                                            const date = new Date(raw);
                                            return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                                        },
=======
>>>>>>> 23cf5aa1bc2c2abe1c6339f71e906666f4fde41d
                                        label: function(ctx) {
                                            const val = ctx.parsed.y !== undefined ? ctx.parsed.y : ctx.parsed;
                                            return ' CO₂: ' + val.toFixed(2) + ' kg';
                                        }
                                    }
                                }
                            },
                            scales: this.chartType === 'pie' ? {} : {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11 }, color: '#9CA3AF', maxRotation: 45 },
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.04)' },
                                    ticks: { font: { size: 11 }, color: '#9CA3AF' },
                                }
                            },
                            interaction: { intersect: false, mode: 'index' },
                        }
                    };

                    if (this.chartType === 'pie') {
                        let transport = 0, energy = 0, food = 0;
                        chartData.forEach(d => {
                            transport += parseFloat(d.transport_emission);
                            energy += parseFloat(d.energy_emission);
                            food += parseFloat(d.consumption_emission);
                        });
                        
                        config.data.labels = ['Transportation', 'Energy', 'Food'];
                        config.data.datasets[0].data = [transport, energy, food];
                        config.data.datasets[0].backgroundColor = ['#ef4444', '#10b981', '#f97316'];
                        config.data.datasets[0].borderColor = '#ffffff';
                        config.data.datasets[0].borderWidth = 2;
                    }

                    this.chartInstance = new Chart(ctx, config);
                }
            }));
        });
    </script>
</x-app-layout>
