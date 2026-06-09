<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name', 'Act4Climate') : config('app.name', 'Act4Climate') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-primary-900 via-primary-700 to-secondary-600 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-secondary-400/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-accent-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 mb-8 text-center animate-fade-in">
            <a href="/" class="inline-flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Act4Climate Logo" class="h-12 w-auto object-contain shrink-0">
                <span class="text-2xl font-bold text-white tracking-tight">Act4Climate</span>
            </a>
            <p class="mt-2 text-sm text-white/60">Track your carbon footprint, protect our planet</p>
        </div>
        <div class="relative z-10 w-full sm:max-w-md animate-slide-up">
            <div class="bg-white/95 backdrop-blur-xl shadow-elevated rounded-2xl px-8 py-8 border border-white/50">
                {{ $slot }}
            </div>
        </div>
        <p class="relative z-10 mt-8 text-xs text-white/40">&copy; {{ date('Y') }} Act4Climate</p>
    </div>
</body>
</html>
