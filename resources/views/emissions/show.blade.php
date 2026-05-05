<x-calculator-layout>
    <x-slot name="title">Your Climate Impact</x-slot>

    <style>
        @media print {
            .no-print { display: none !important; }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: white !important;
            }
            .min-h-screen { min-height: auto !important; padding-bottom: 0 !important; }
            @page { margin: 15mm; }
        }
    </style>

    @php
        // Use daily kg CO2 directly
        $dailyKg = $emission->total_emission;
        $tKg = $emission->transport_emission;
        $eKg = $emission->energy_emission;
        $cKg = $emission->consumption_emission;
        
        $target = 8.2; // 8.2 kg per day
        $isOver = $dailyKg > $target;
        $percentOfTarget = ($dailyKg / $target) * 100;
        
        // Avoid division by zero for percentages
        $tPct = $dailyKg > 0 ? ($tKg / $dailyKg) * 100 : 0;
        $ePct = $dailyKg > 0 ? ($eKg / $dailyKg) * 100 : 0;
        $cPct = $dailyKg > 0 ? ($cKg / $dailyKg) * 100 : 0;
    @endphp

    <!-- Use a full-width white background layout to match the clean mockup -->
    <div class="min-h-screen bg-white text-center pb-20 pt-10" style="margin-top: -2rem;">
        <div class="max-w-4xl mx-auto px-4">
            
            <!-- Icon Warning / Success -->
            <div class="flex justify-center mb-6">
                @if($isOver)
                    <div class="w-20 h-20 rounded-full bg-red-500 flex items-center justify-center shadow-[0_10px_20px_rgba(239,68,68,0.3)]">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                @else
                    <div class="w-20 h-20 rounded-full bg-[#2D5A4C] flex items-center justify-center shadow-[0_10px_20px_rgba(45,90,76,0.3)]">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @endif
            </div>

            <!-- Title & Subtitle -->
            <h1 class="text-4xl md:text-5xl font-black mb-4 {{ $isOver ? 'text-red-500' : 'text-[#2D5A4C]' }} tracking-tight">
                {{ $isOver ? "Oh No! You're Over Target!" : "Great Job! You're On Target!" }}
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto mb-10 text-sm md:text-base leading-relaxed">
                Your current carbon footprint {{ $isOver ? 'exceeds' : 'is below' }} the sustainable target. The average person should aim for 5-8.2 kg of CO₂ per day to combat climate change effectively. Your actions today shape tomorrow's climate.
            </p>

            <!-- Main Impact Card -->
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.08)] border-2 {{ $isOver ? 'border-red-100' : 'border-green-100' }} relative overflow-hidden mb-16 mx-auto max-w-3xl text-left">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10">
                    <div class="flex-1 text-center md:text-left md:border-r border-gray-100 pb-6 md:pb-0 md:pr-8">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Your Total Carbon Footprint</p>
                        <div class="flex items-baseline justify-center md:justify-start gap-2">
                            <span class="text-6xl md:text-7xl font-black {{ $isOver ? 'text-red-500' : 'text-[#2D5A4C]' }} tracking-tighter">{{ number_format($dailyKg, 1) }}</span>
                            <span class="text-xl md:text-2xl font-bold text-gray-500">kg</span>
                        </div>
                        <p class="text-gray-400 text-sm mt-1 font-medium">CO₂ per day</p>
                    </div>
                    <div class="flex-1 text-center md:text-right">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                            {{ $isOver ? 'Above Target' : 'Of Target' }}
                        </p>
                        <div class="text-5xl md:text-6xl font-black text-orange-400 tracking-tighter mb-1">
                            {{ number_format($percentOfTarget, 0) }}%
                        </div>
                        <p class="text-gray-400 text-sm font-medium">of recommended limit</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="relative pt-6">
                    <div class="h-4 w-full bg-gray-100 rounded-full overflow-hidden flex shadow-inner">
                        <!-- Gradient fill -->
                        <div class="h-full rounded-full bg-gradient-to-r from-green-300 via-yellow-400 to-red-400" style="width: {{ min(100, ($dailyKg / 25) * 100) }}%"></div>
                    </div>
                    <!-- Markers -->
                    <div class="flex justify-between text-[10px] md:text-[11px] font-bold text-gray-400 mt-4 uppercase tracking-widest">
                        <span>0 kg</span>
                        <span class="text-gray-800">Target: 8.2 kg</span>
                        <span>25+ kg</span>
                    </div>
                    <!-- Visual Target Line Marker -->
                    <div class="absolute top-5 bottom-8 border-l-2 border-gray-800/20" style="left: 30%;"></div>
                </div>
            </div>

            <!-- Breakdown Title -->
            <h2 class="text-2xl md:text-3xl font-black text-[#1a382e] mb-4">Footprint Breakdown by Category</h2>
            <p class="text-gray-500 max-w-2xl mx-auto mb-10 text-sm md:text-base leading-relaxed">
                Understanding where your emissions come from is the first step to reducing your impact. Here's how your lifestyle choices contribute to your carbon footprint.
            </p>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <!-- Transport Card -->
                <div class="bg-white rounded-3xl shadow-[0_4px_24px_rgb(0,0,0,0.06)] border-t-[6px] border-blue-500 p-6 md:p-8 flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/20 text-white">
                            <svg class="w-7 h-7 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-blue-600 tracking-tighter">{{ number_format($tPct, 0) }}%</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">of total</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">Travel & Transportation</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8 flex-grow">
                        Your transportation choices contribute significantly to your carbon footprint. Frequent air travel and daily car commutes are the primary contributors in this category.
                    </p>
                    <div class="flex justify-between items-baseline border-b-2 border-gray-100 pb-3 mb-5">
                        <span class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Carbon Output</span>
                        <span class="text-base font-black text-blue-600">{{ number_format($tKg, 1) }} kg/day</span>
                    </div>
                    <div class="bg-blue-50/80 rounded-2xl p-4 mt-auto">
                        <p class="text-xs font-black text-blue-800 flex items-center gap-1.5 mb-2 uppercase tracking-wide">
                            Quick Tip 
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
                        </p>
                        <p class="text-xs text-blue-900/70 leading-relaxed font-medium">Consider carpooling, public transport, or cycling for short distances. Each avoided car trip saves approximately 0.4 kg CO₂.</p>
                    </div>
                </div>

                <!-- Energy Card -->
                <div class="bg-white rounded-3xl shadow-[0_4px_24px_rgb(0,0,0,0.06)] border-t-[6px] border-yellow-500 p-6 md:p-8 flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-yellow-500 flex items-center justify-center shadow-lg shadow-yellow-500/20 text-white">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-yellow-600 tracking-tighter">{{ number_format($ePct, 0) }}%</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">of total</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">Energy & Utilities</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8 flex-grow">
                        Home energy consumption from heating, cooling, and electricity usage plays a major role. Inefficient appliances and heating systems increase your environmental impact.
                    </p>
                    <div class="flex justify-between items-baseline border-b-2 border-gray-100 pb-3 mb-5">
                        <span class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Carbon Output</span>
                        <span class="text-base font-black text-yellow-600">{{ number_format($eKg, 1) }} kg/day</span>
                    </div>
                    <div class="bg-yellow-50/80 rounded-2xl p-4 mt-auto">
                        <p class="text-xs font-black text-yellow-800 flex items-center gap-1.5 mb-2 uppercase tracking-wide">
                            Quick Tip 
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
                        </p>
                        <p class="text-xs text-yellow-900/70 leading-relaxed font-medium">Switch to LED bulbs and adjust your thermostat by 2°C. This simple change can reduce emissions by up to 15%.</p>
                    </div>
                </div>

                <!-- Food Card -->
                <div class="bg-white rounded-3xl shadow-[0_4px_24px_rgb(0,0,0,0.06)] border-t-[6px] border-[#2D5A4C] p-6 md:p-8 flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-[#2D5A4C] flex items-center justify-center shadow-lg shadow-[#2D5A4C]/20 text-white">
                            <!-- Fork/Knife Icon - Updated to match mockup using generic food icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-[#2D5A4C] tracking-tighter">{{ number_format($cPct, 0) }}%</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">of total</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">Food & Consumption</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8 flex-grow">
                        Your dietary choices matter. Meat-heavy diets, especially red meat, and food waste contribute substantially to greenhouse gas emissions through production and transportation.
                    </p>
                    <div class="flex justify-between items-baseline border-b-2 border-gray-100 pb-3 mb-5">
                        <span class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Carbon Output</span>
                        <span class="text-base font-black text-[#2D5A4C]">{{ number_format($cKg, 1) }} kg/day</span>
                    </div>
                    <div class="bg-[#2D5A4C]/10 rounded-2xl p-4 mt-auto">
                        <p class="text-xs font-black text-[#2D5A4C] flex items-center gap-1.5 mb-2 uppercase tracking-wide">
                            Quick Tip 
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
                        </p>
                        <p class="text-xs text-[#2D5A4C]/80 leading-relaxed font-medium">Try Meatless Mondays and reduce food waste. Going plant-based one day per week saves 3.6 kg CO₂ weekly.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex flex-col sm:flex-row justify-center items-center gap-4 no-print">
                <button onclick="window.print()" class="inline-flex items-center justify-center gap-3 bg-white border-2 border-[#2D5A4C] text-[#2D5A4C] hover:bg-gray-50 px-10 py-4 rounded-xl font-black text-lg transition-all shadow-[0_4px_15px_rgba(0,0,0,0.05)] hover:-translate-y-1 w-full sm:w-auto cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print / Save PDF
                </button>
                
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-3 bg-[#2D5A4C] hover:bg-[#1a382e] text-white px-10 py-4 rounded-xl font-black text-lg transition-all shadow-[0_8px_20px_rgba(45,90,76,0.3)] hover:shadow-[0_12px_25px_rgba(45,90,76,0.4)] hover:-translate-y-1 w-full sm:w-auto">
                    Return to Dashboard
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            
        </div>
    </div>
</x-calculator-layout>
