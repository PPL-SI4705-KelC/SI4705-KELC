<x-app-layout>
    <x-slot name="title">Communities</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-content">Climate Communities 🌍</h1>
            <p class="text-base text-content-muted mt-1">Connect with eco-minded people</p>
        </div>
    </x-slot>

    <div class="space-y-6 animate-fade-in pb-10">
        @forelse($communities as $community)
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col md:flex-row w-full">
            {{-- Cover Image Column --}}
            <div class="w-full md:w-[320px] shrink-0 relative bg-gray-50 flex items-center justify-center min-h-[200px]">
                @if($community->cover_image)
                    <img src="{{ asset('storage/' . $community->cover_image) }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full min-h-[200px] bg-gradient-to-br from-[#9C27B0] to-[#E040FB] flex items-center justify-center relative overflow-hidden">
                        {{-- Repeating watermark text --}}
                        <div class="absolute inset-0 opacity-10 flex flex-col justify-around rotate-[-12deg] scale-125 pointer-events-none select-none">
                            <div class="whitespace-nowrap text-white font-black text-2xl tracking-widest">ACT4CLIMATE ACT4CLIMATE</div>
                            <div class="whitespace-nowrap text-white font-black text-2xl tracking-widest">ACT4CLIMATE ACT4CLIMATE</div>
                            <div class="whitespace-nowrap text-white font-black text-2xl tracking-widest">ACT4CLIMATE ACT4CLIMATE</div>
                        </div>
                        {{-- Logo/Icon overlay --}}
                        <div class="relative z-10 w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 shadow-lg">
                            <span class="text-3xl">🌿</span>
                        </div>
                    </div>
                @endif

                {{-- Active/Inactive Badge overlay --}}
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm {{ $community->is_active ? 'bg-[#f0f9f5] text-[#2A5C4D]' : 'bg-gray-800 text-white' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $community->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        {{ $community->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            {{-- Info Column --}}
            <div class="flex-1 p-6 md:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-snug hover:text-[#2A5C4D] transition-colors">
                                <a href="{{ route('community.show', $community) }}">{{ $community->name }}</a>
                            </h3>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2 text-xs text-gray-400">
                                <span class="flex items-center gap-1 text-[#2D5A4C] font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                                    {{ $community->slug }}
                                </span>
                                <span>·</span>
                                <span>Created {{ $community->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        {{-- Members pill --}}
                        <div class="bg-[#f0f9f5] rounded-2xl px-4 py-2 border border-emerald-50 text-center shrink-0">
                            <p class="text-[9px] font-bold text-[#2D5A4C]/70 uppercase tracking-widest leading-none">MEMBERS</p>
                            <p class="text-lg font-black text-[#2D5A4C] mt-1 leading-none">{{ number_format($community->members_count ?? $community->member_count) }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed mt-4">
                        {{ $community->description ?? 'No description provided. Join us to discuss climate action.' }}
                    </p>
                </div>

                {{-- Actions Row --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 pt-6 mt-6">
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- View Details (Main action) --}}
                        <a href="{{ route('community.show', $community) }}" class="inline-flex items-center gap-2 border border-gray-200 hover:bg-gray-50 text-gray-700 bg-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>

                        {{-- Membership Join/Leave --}}
                        @if(!in_array($community->id, $myCommunities))
                            <form method="POST" action="{{ route('community.join', $community) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-[#2D5A4C] hover:bg-[#1e4237] text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Join
                                </button>
                            </form>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 border border-emerald-100 text-[#2D5A4C] rounded-xl text-xs font-bold shadow-sm">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Joined Member
                                </span>
                                <form method="POST" action="{{ route('community.leave', $community) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 border border-red-200 hover:bg-red-50 text-red-500 px-5 py-2.5 rounded-xl text-xs font-bold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Leave
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Admin Actions --}}
                        @if(Auth::user()->isAdmin())
                            <div class="flex items-center gap-2 border-l border-gray-200 pl-3">
                                <a href="{{ route('admin.communities.edit', $community) }}" class="inline-flex items-center gap-2 bg-[#2D5A4C] hover:bg-[#1e4237] text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    Edit Details
                                </a>

                                <form method="POST" action="{{ route('admin.communities.toggle-status', $community) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                                        @if($community->is_active)
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            Deactivate
                                        @else
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Reactivate
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl w-full">
            <span class="text-5xl">🌱</span>
            <p class="text-content-muted mt-4">No communities yet. Stay tuned!</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $communities->links() }}</div>
</x-app-layout>
