<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' . config('app.name', 'Act4Climate') : config('app.name', 'Act4Climate') }}</title>
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
                @if (isset($header))
                    {{ $header }}
                @else
                    {{-- Search Bar --}}
                    @if(!request()->routeIs('admin.*') && !request()->routeIs('emissions.*'))
                    <div class="relative w-full max-w-[600px]">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Search" class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-[15px] shadow-sm transition">
                    </div>
                    @else
                    <div></div>
                    @endif
                    
                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3">
                        @if(!request()->routeIs('admin.*'))
                            <a href="{{ route('community.redirect') }}" class="w-[46px] h-[46px] rounded-full bg-[#2A5C4D] text-white flex items-center justify-center hover:bg-[#1e4237] transition shadow-sm" title="Community Chat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </a>
                        @endif
                        <div class="relative" x-data="notificationDropdown()" @click.outside="open = false">
                            <button @click="toggle()" class="w-[46px] h-[46px] rounded-full bg-[#2A5C4D] text-white flex items-center justify-center hover:bg-[#1e4237] transition shadow-sm relative">
                                <template x-if="unreadCount > 0">
                                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[9px] font-black flex items-center justify-center border-2 border-[#2A5C4D] shadow-sm" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                                </template>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </button>

                            <!-- Notification Dropdown Panel -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-3 w-[380px] bg-white rounded-3xl shadow-[0_10px_40px_rgb(0,0,0,0.12)] border border-gray-100 z-50 overflow-hidden"
                                 style="display: none;">

                                <!-- Header -->
                                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                    <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                                    <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-[11px] font-bold text-[#2A5C4D] hover:text-[#1e4237] transition-colors">
                                        Mark all as read
                                    </button>
                                </div>

                                <!-- Notifications List -->
                                <div class="max-h-[360px] overflow-y-auto">
                                    <template x-if="loading">
                                        <div class="flex items-center justify-center py-10">
                                            <svg class="w-5 h-5 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                        </div>
                                    </template>

                                    <template x-if="!loading && notifications.length === 0">
                                        <div class="flex flex-col items-center justify-center py-10 gap-2">
                                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                            <p class="text-xs text-gray-400 font-medium">No notifications yet</p>
                                        </div>
                                    </template>

                                    <template x-if="!loading && notifications.length > 0">
                                        <div>
                                            <template x-for="notif in notifications" :key="notif.id">
                                                <a :href="notif.link || '#'"
                                                   @click="markAsRead(notif)"
                                                   class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 cursor-pointer"
                                                   :class="!notif.is_read ? 'bg-[#f0faf5]' : ''">
                                                    <span class="text-lg mt-0.5 shrink-0 select-none" x-text="notif.icon"></span>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-[13px] text-gray-800 leading-snug" :class="!notif.is_read ? 'font-bold' : 'font-medium'" x-text="notif.message"></p>
                                                        <p class="text-[10px] text-gray-400 font-medium mt-1" x-text="notif.time_ago"></p>
                                                    </div>
                                                    <template x-if="!notif.is_read">
                                                        <span class="w-2 h-2 rounded-full bg-[#2A5C4D] shrink-0 mt-2"></span>
                                                    </template>
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <!-- Profile Dropdown -->
                        <div class="relative ml-2" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 bg-white rounded-full p-1.5 pr-4 border border-gray-200 shadow-sm hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20">
                                <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E2E8F0&color=2A5C4D" alt="Avatar" class="w-full h-full object-cover">
                                    @endif
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
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E2E8F0&color=2A5C4D" alt="Avatar" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-[13px] font-bold text-gray-900 truncate">{{ '@' . strtolower(str_replace(' ', '', Auth::user()->name)) }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </div>
    
                                <!-- Menu Links (Styled as outlined pills) -->
                                <div class="flex flex-col gap-2">
                                    @if(!Auth::user()->isAdmin())
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-bold text-[#2A5C4D] bg-white border border-gray-200 rounded-2xl hover:border-[#2A5C4D] hover:shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('journey.index') }}" class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-bold text-[#2A5C4D] bg-white border border-gray-200 rounded-2xl hover:border-[#2A5C4D] hover:shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        Journey
                                    </a>
                                    @endif
    
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
                @endif
            </header>

            {{-- Page Content --}}
            <main class="flex-1 px-8 pb-12 pt-2">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function notificationDropdown() {
            return {
                open: false,
                loading: false,
                notifications: [],
                unreadCount: 0,

                init() {
                    this.fetchUnreadCount();
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },

                async fetchUnreadCount() {
                    try {
                        const res = await fetch('{{ route("notifications.unreadCount") }}');
                        const data = await res.json();
                        this.unreadCount = data.count;
                    } catch (e) {
                        console.error('Failed to fetch unread count', e);
                    }
                },

                async fetchNotifications() {
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route("notifications.index") }}');
                        this.notifications = await res.json();
                    } catch (e) {
                        console.error('Failed to fetch notifications', e);
                    } finally {
                        this.loading = false;
                    }
                },

                async markAsRead(notif) {
                    if (!notif.is_read) {
                        try {
                            await fetch('/notifications/' + notif.id + '/read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                },
                            });
                            notif.is_read = true;
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        } catch (e) {
                            console.error('Failed to mark as read', e);
                        }
                    }
                },

                async markAllAsRead() {
                    try {
                        await fetch('{{ route("notifications.readAll") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        });
                        this.notifications.forEach(n => n.is_read = true);
                        this.unreadCount = 0;
                    } catch (e) {
                        console.error('Failed to mark all as read', e);
                    }
                },
            };
        }
    </script>
    <!-- Global Destructive Action Confirmation Modal -->
    <div id="global-confirm-modal" class="fixed inset-0 z-50 overflow-y-auto hidden items-center justify-center p-4 sm:p-0" role="dialog" aria-modal="true">
        <!-- Backdrop with blur -->
        <div id="global-confirm-backdrop" class="fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0"></div>

        <!-- Modal Panel Card -->
        <div id="global-confirm-card" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl border border-gray-100 transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-md z-10 scale-95 opacity-0 -translate-y-4 p-8 flex flex-col items-center">
            
            <!-- Warning Trash / Alert Icon -->
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500 mb-6 shrink-0 shadow-inner">
                <svg class="h-8 w-8 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-3 text-center leading-snug tracking-tight">Are you absolutely sure?</h3>
            
            <p id="global-confirm-message" class="text-sm text-gray-500 font-medium text-center px-2 leading-relaxed mb-8">
                This action is permanent and cannot be undone. Do you really want to delete this?
            </p>

            <!-- Buttons Row -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-center w-full gap-3 select-none">
                <button type="button" id="global-confirm-cancel"
                        class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-3.5 text-xs font-bold text-gray-700 shadow-sm border border-gray-200 hover:bg-gray-50 hover:text-gray-950 transition-all duration-150 active:scale-[0.98] sm:flex-1">
                    No, cancel
                </button>
                <button type="button" id="global-confirm-btn"
                        class="inline-flex w-full justify-center rounded-xl bg-[#EF4444] hover:bg-[#DC2626] active:bg-[#B91C1C] px-5 py-3.5 text-xs font-bold text-white shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98] sm:flex-1">
                    Yes, delete
                </button>
            </div>
        </div>
    </div>

    <!-- Global Custom Alert Modal -->
    <div id="global-alert-modal" class="fixed inset-0 z-50 overflow-y-auto hidden items-center justify-center p-4 sm:p-0" role="dialog" aria-modal="true">
        <!-- Backdrop with blur -->
        <div id="global-alert-backdrop" class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0"></div>

        <!-- Modal Panel Card -->
        <div id="global-alert-card" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl border border-gray-100 transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-md z-10 scale-95 opacity-0 -translate-y-4 p-8 flex flex-col items-center">
            
            <!-- Warning / Triangle Alert Icon -->
            <div id="global-alert-icon-container" class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-500 mb-6 shrink-0 shadow-inner">
                <svg class="h-8 w-8 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h3 id="global-alert-title" class="text-xl font-bold text-gray-900 mb-2.5 text-center leading-snug tracking-tight">Notification</h3>
            
            <p id="global-alert-message" class="text-sm text-gray-500 font-medium text-center px-2 leading-relaxed mb-8">
                Something went wrong.
            </p>

            <!-- Buttons Row -->
            <div class="flex items-center justify-center w-full select-none">
                <button type="button" id="global-alert-btn"
                        class="inline-flex w-full justify-center rounded-xl bg-[#2D5A4C] hover:bg-[#1e4237] active:bg-[#153027] active:scale-[0.98] px-6 py-3.5 text-xs font-bold text-white shadow-md hover:shadow-lg transition-all duration-150">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        // Global Confirm Modal Controller
        function showConfirmModal(message, onConfirm) {
            const modal = document.getElementById('global-confirm-modal');
            const backdrop = document.getElementById('global-confirm-backdrop');
            const card = document.getElementById('global-confirm-card');
            const messageEl = document.getElementById('global-confirm-message');
            const confirmBtn = document.getElementById('global-confirm-btn');
            const cancelBtn = document.getElementById('global-confirm-cancel');
            
            // Set dynamic message
            messageEl.textContent = message;
            
            // Show modal container
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Force reflow for CSS transition
            void modal.offsetWidth;
            
            // Transition in
            backdrop.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('scale-95', 'scale-100');
            card.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('-translate-y-4', 'translate-y-0');
            
            const cleanUp = () => {
                // Transition out
                backdrop.classList.replace('opacity-100', 'opacity-0');
                card.classList.replace('scale-100', 'scale-95');
                card.classList.replace('opacity-100', 'opacity-0');
                card.classList.replace('translate-y-0', '-translate-y-4');
                
                // Hide after transition ends (300ms matches duration-300)
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
                
                // Unbind listeners to prevent memory leaks/duplicate calls
                confirmBtn.removeEventListener('click', handleConfirm);
                cancelBtn.removeEventListener('click', handleCancel);
                backdrop.removeEventListener('click', handleCancel);
            };
            
            function handleConfirm() {
                cleanUp();
                onConfirm();
            }
            
            function handleCancel() {
                cleanUp();
            }
            
            // Bind action handlers
            confirmBtn.addEventListener('click', handleConfirm);
            cancelBtn.addEventListener('click', handleCancel);
            backdrop.addEventListener('click', handleCancel);
        }

        // Global Custom Alert Controller
        function showCustomAlert(message, title = 'Notification', type = null) {
            const iconContainer = document.getElementById('global-alert-icon-container');
            if (iconContainer) {
                const isSuccess = (type === 'success') || 
                                  title.toLowerCase().includes('berhasil') || 
                                  title.toLowerCase().includes('success');
                
                if (isSuccess) {
                    iconContainer.className = "flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-500 mb-6 shrink-0 shadow-inner";
                    iconContainer.innerHTML = `
                        <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    `;
                } else {
                    iconContainer.className = "flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-500 mb-6 shrink-0 shadow-inner";
                    iconContainer.innerHTML = `
                        <svg class="h-8 w-8 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    `;
                }
            }

            const modal = document.getElementById('global-alert-modal');
            const backdrop = document.getElementById('global-alert-backdrop');
            const card = document.getElementById('global-alert-card');
            const titleEl = document.getElementById('global-alert-title');
            const messageEl = document.getElementById('global-alert-message');
            const btn = document.getElementById('global-alert-btn');
            
            // Set dynamic message
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Show modal container
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Force reflow
            void modal.offsetWidth;
            
            // Transition in
            backdrop.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('scale-95', 'scale-100');
            card.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('-translate-y-4', 'translate-y-0');
            
            const cleanUp = () => {
                // Transition out
                backdrop.classList.replace('opacity-100', 'opacity-0');
                card.classList.replace('scale-100', 'scale-95');
                card.classList.replace('opacity-100', 'opacity-0');
                card.classList.replace('translate-y-0', '-translate-y-4');
                
                // Hide after transition ends
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
                
                btn.removeEventListener('click', cleanUp);
                backdrop.removeEventListener('click', cleanUp);
            };
            
            // Bind action handlers
            btn.addEventListener('click', cleanUp);
            backdrop.addEventListener('click', cleanUp);
        }

        // Intercept all form submissions that have a data-confirm attribute
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.hasAttribute('data-confirm')) {
                // If the user already confirmed, let it submit normally
                if (form.dataset.confirmed === 'true') {
                    return;
                }
                
                // Prevent standard submit
                e.preventDefault();
                
                // Show our custom modal
                const message = form.getAttribute('data-confirm');
                showConfirmModal(message, () => {
                    form.dataset.confirmed = 'true';
                    form.submit();
                });
            }
        });
    </script>
</body>
</html>
