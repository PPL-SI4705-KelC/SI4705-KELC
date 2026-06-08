<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Act4Climate') }}</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<<<<<<< HEAD
<body class="font-sans antialiased text-gray-900 bg-gray-50">
=======
<body class="font-sans antialiased text-gray-900 bg-gray-50">    
    <!-- Global Toast Notification System -->
    @if(session('info') || session('success') || session('error'))
        @php
            $type = session('error') ? 'error' : (session('success') ? 'success' : 'info');
            $title = ucfirst($type);
            $message = session($type);
            
            // Define colors based on type
            $borderColor = $type === 'error' ? 'bg-red-400' : ($type === 'success' ? 'bg-green-400' : 'bg-cyan-400');
            $iconColor = $type === 'error' ? 'text-red-400' : ($type === 'success' ? 'text-green-400' : 'text-cyan-400');
        @endphp

        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-[-20px] scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[100] w-full max-w-sm">
             
            <div class="relative flex items-center p-4 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
                <!-- Left Color Bar -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderColor }}"></div>
                
                <!-- Icon -->
                <div class="ml-2 mr-4 {{ $iconColor }}">
                    @if($type === 'error')
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    @elseif($type === 'success')
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    @else
                        <!-- Info Icon matching reference -->
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v1a1 1 0 002 0V6zm-1 3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    @endif
                </div>
                
                <!-- Text Content -->
                <div class="flex-1 min-w-0 pr-4">
                    <h3 class="text-gray-800 font-bold text-base mb-0.5">{{ $title }}</h3>
                    <p class="text-gray-500 text-sm leading-snug">
                        {{ $message }}
                    </p>
                </div>
                
                <!-- Close Button -->
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

>>>>>>> ac7a16f12a0ab597fb817dc8f456037e0ba9679f
    <main>
        {{ $slot }}
    </main>
</body>
</html>
