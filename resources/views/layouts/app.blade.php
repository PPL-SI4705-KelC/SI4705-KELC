<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Act4Climate') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background-color: #fafbfc; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#fafbfc]">
    <div class="min-h-screen flex">
        
        @include('layouts.side-bar')

        <div class="flex-1 sm:ml-[280px] flex flex-col min-h-screen">
            {{-- Top Header --}}
            <header class="h-28 px-8 flex items-center justify-between shrink-0">
                {{-- Search Bar --}}
                <div class="relative w-full max-w-[600px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Search" class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-[15px] shadow-sm transition">
                </div>
                
                {{-- Right Actions --}}
                <div class="flex items-center gap-3">
                    <button class="w-[46px] h-[46px] rounded-full bg-[#2A5C4D] text-white flex items-center justify-center hover:bg-[#1e4237] transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </button>
                    <button class="w-[46px] h-[46px] rounded-full bg-[#2A5C4D] text-white flex items-center justify-center hover:bg-[#1e4237] transition shadow-sm relative">
                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#2A5C4D]"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative ml-2" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 bg-white rounded-full p-1.5 pr-4 border border-gray-200 shadow-sm hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20">
                            <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E2E8F0&color=2A5C4D" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-[0_10px_40px_rgb(0,0,0,0.12)] border border-gray-100 z-50 p-4"
                             style="display: none;">
                            
                            <!-- User Info Header -->
                            <div class="flex items-center gap-3 mb-4 cursor-pointer" @click="open = false">
                                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E2E8F0&color=2A5C4D" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[13px] font-bold text-gray-900 truncate">{{ '@' . strtolower(str_replace(' ', '', Auth::user()->name)) }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </div>

                            <!-- Menu Links (Styled as outlined pills) -->
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-bold text-[#2A5C4D] bg-white border border-gray-200 rounded-2xl hover:border-[#2A5C4D] hover:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <a href="{{ route('journey.index') }}" class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-bold text-[#2A5C4D] bg-white border border-gray-200 rounded-2xl hover:border-[#2A5C4D] hover:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    Journey
                                </a>

                                <!-- Logout Action -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-bold text-[#2A5C4D] bg-white border border-gray-200 rounded-2xl hover:border-red-500 hover:text-red-600 hover:shadow-sm transition-all text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        LogOut
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 px-8 pb-12 pt-2">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
