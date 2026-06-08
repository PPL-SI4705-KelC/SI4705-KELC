<x-calculator-layout x-data="{ showConfirmModal: false }">
    <x-slot name="title">Profile Settings - Act4Climate</x-slot>

    <!-- Custom Top Header -->
    <div class="w-full bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4 sticky top-0 z-50 shadow-sm">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Act4Climate" class="h-8 w-auto">
            <span class="text-xl font-black text-[#2A5C4D] tracking-tight">Act4Climate</span>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 md:py-12">
        <!-- Header Text -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Profile Settings</h1>
            <p class="text-gray-500 mt-1 font-medium">Manage your account information and preferences</p>
        </div>

        <!-- Two Column Layout -->
        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Left Column: User Card -->
            <div class="w-full md:w-[320px] shrink-0">
                <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-[0_4px_24px_rgb(0,0,0,0.04)] text-center relative">
                    <!-- Avatar -->
                    <div class="relative w-32 h-32 mx-auto mb-5">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-gray-50 shadow-sm bg-gray-200">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E2E8F0&color=2A5C4D&size=128" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <!-- Camera Icon -->
                        <button class="absolute bottom-1 right-1 w-9 h-9 bg-[#2A5C4D] rounded-full border-[3px] border-white flex items-center justify-center text-white hover:bg-[#1e4237] transition shadow-sm cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>
                    
                    <!-- Name & Email -->
                    <h2 class="text-[22px] font-black text-gray-900 tracking-tight leading-none mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-[13px] text-gray-500 mb-5">{{ Auth::user()->email }}</p>

                    <!-- Badge -->
                    <div class="inline-flex items-center gap-1.5 bg-[#e8f3ef] text-[#2A5C4D] px-4 py-1.5 rounded-full text-xs font-bold mb-8 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0010.399 0 12.014 12.014 0 000 10.399a12.015 12.015 0 001.046.901 12.014 12.014 0 009.353 9.353c.3.05.6.095.901.107A12.014 12.014 0 0020 10.399a12.015 12.015 0 00-.107-.901A12.014 12.014 0 0010.539 1.046zM10 15a5 5 0 100-10 5 5 0 000 10z" clip-rule="evenodd"/></svg>
                        {{ Auth::user()->journey_title ?? 'Eco Beginner' }}
                    </div>

                    <!-- Divider -->
                    <hr class="border-gray-100 mb-6">

                    <!-- Stats -->
                    @php
                        // Hitung emisi yang dikurangi berdasarkan target harian wajar (8.2 kg/hari)
                        $emissionsCount = Auth::user()->emissions()->count();
                        $targetEmission = $emissionsCount * 8.2;
                        $actualEmission = Auth::user()->emissions()->sum('total_emission');
                        $co2Reduced = max(0, $targetEmission - $actualEmission);
                    @endphp
                    <div class="space-y-4 text-[13px]">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Joined since</span>
                            <span class="font-bold text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Total CO₂ reduced</span>
                            <span class="font-bold text-[#2A5C4D]">{{ number_format($co2Reduced, 1) }} kg</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Card -->
            <div class="flex-1">
                <div class="bg-white rounded-[24px] p-8 md:p-10 border border-gray-100 shadow-[0_4px_24px_rgb(0,0,0,0.04)] h-full relative">
                    <h3 class="text-xl font-black text-gray-900 tracking-tight mb-8">Personal Information</h3>

                    <form id="profile-update-form" method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 transition-colors placeholder:text-gray-400 font-medium" placeholder="Username...">
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 transition-colors placeholder:text-gray-400 font-medium" placeholder="example@mail.com">
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Telp -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telp</label>
                            <input type="text" name="telp" value="+62 812 3456 7890" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 transition-colors placeholder:text-gray-400 font-medium" placeholder="+62 812 3456 7890">
                        </div>

                        <!-- City & Postal Code -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                                <div class="relative">
                                    <select name="city" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 appearance-none transition-colors font-medium">
                                        <option>Jakarta</option>
                                        <option>Bandung</option>
                                        <option>Surabaya</option>
                                        <option>Yogyakarta</option>
                                        <option>Bali</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" value="12345" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 transition-colors placeholder:text-gray-400 font-medium" placeholder="12345">
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] text-gray-900 transition-colors placeholder:text-gray-400 resize-none font-medium" placeholder="Jl. Sudirman No. 123, Jakarta Pusat">Jl. Sudirman No. 123, Jakarta Pusat</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 pt-6 mt-8">
                            <button type="button" @click="showConfirmModal = true" class="flex-1 inline-flex items-center justify-center gap-2 bg-[#2A5C4D] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#1e4237] transition shadow-[0_4px_14px_rgba(42,92,77,0.3)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Save Changes
                            </button>
                            
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center bg-white border-2 border-gray-200 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 transition text-center shrink-0">
                                Cancel
                            </a>
                        </div>
                        @if (session('status') === 'profile-updated')
                            <!-- Floating Toast Notification -->
                            <div x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition:enter="transform ease-out duration-300 transition"
                                 x-transition:enter-start="translate-y-10 opacity-0"
                                 x-transition:enter-end="translate-y-0 opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-2"
                                 x-init="setTimeout(() => show = false, 4000)" 
                                 class="fixed bottom-8 right-8 md:bottom-10 md:right-10 z-50 flex items-center gap-4 bg-[#2A5C4D] text-white px-5 py-4 rounded-2xl shadow-[0_10px_40px_rgba(42,92,77,0.3)] border border-[#3b7362]">
                                <div class="flex-shrink-0 bg-white/20 rounded-full p-1.5">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-[14px] font-bold leading-tight">Berhasil Disimpan!</h4>
                                    <p class="text-[12px] text-white/80 mt-0.5 font-medium">Pengaturan profil Anda telah diperbarui.</p>
                                </div>
                                <button type="button" @click="show = false" class="ml-4 text-white/60 hover:text-white transition p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div x-show="showConfirmModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" 
             @click="showConfirmModal = false"></div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-center shadow-2xl transition-all max-w-sm w-full">
                
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#e8f3ef] mb-6">
                    <svg class="h-10 w-10 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-gray-900 mb-2">Simpan Perubahan?</h3>
                <p class="text-gray-500 mb-8 font-medium">Apakah Anda yakin ingin menyimpan perubahan pada profil Anda?</p>

                <div class="flex flex-col gap-3">
                    <button type="button" 
                            @click="document.getElementById('profile-update-form').submit()" 
                            class="w-full bg-[#2A5C4D] text-white py-4 rounded-2xl font-bold hover:bg-[#1e4237] transition shadow-lg shadow-[#2A5C4D]/20">
                        Simpan
                    </button>
                    <button type="button" 
                            @click="showConfirmModal = false" 
                            class="w-full bg-white border-2 border-gray-100 text-gray-500 py-4 rounded-2xl font-bold hover:bg-gray-50 transition">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-calculator-layout>
