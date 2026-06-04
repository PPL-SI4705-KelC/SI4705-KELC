<x-app-layout>
    <x-slot name="title">Community Management</x-slot>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <h1 class="text-xl font-bold text-content">Community Management</h1>
                <p class="text-sm text-content-muted">Oversee, edit, and moderate climate action communities</p>
            </div>
            <div>
                <a href="{{ route('admin.communities.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#2A5C4D] hover:bg-[#1e4237] text-white px-5 py-3 rounded-xl font-bold transition shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Create Community
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 animate-fade-in pb-10">
        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: Total Communities --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#f0f9f5] flex items-center justify-center text-[#2A5C4D] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL COMMUNITIES</p>
                    <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalCommunities }}</p>
                </div>
            </div>

            {{-- Card 2: Active Members --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#f0f9f5] flex items-center justify-center text-[#2A5C4D] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ACTIVE MEMBERS</p>
                    <p class="text-2xl font-black text-gray-900 mt-1">{{ $formattedMembers }}</p>
                </div>
            </div>
        </div>

        {{-- Filters & Search row --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
            <form method="GET" action="{{ route('admin.communities') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4 justify-between w-full">
                {{-- Search --}}
                <div class="flex flex-1 items-center gap-2">
                    <div class="relative w-full max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or description..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/15 focus:border-[#2A5C4D] text-xs font-semibold shadow-sm transition">
                    </div>
                    <button type="submit" class="bg-[#2A5C4D] hover:bg-[#1e4237] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                        Search
                    </button>
                    @if(request()->filled('search') || request()->filled('status') || request()->filled('sort'))
                        <a href="{{ route('admin.communities') }}" class="btn-ghost text-xs border border-gray-200 px-3 py-2.5 rounded-xl hover:bg-gray-100">Reset</a>
                    @endif
                </div>

                {{-- Dropdown Filters --}}
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider whitespace-nowrap">Filter By:</span>
                        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/15 focus:border-[#2A5C4D] shadow-sm">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Status: All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Status: Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Status: Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <select name="sort" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/15 focus:border-[#2A5C4D] shadow-sm">
                            <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Sort: Most Recent</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Sort: Popular (Members)</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort: Alphabetical</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Communities list --}}
        <div class="space-y-6">
            @forelse($communities as $community)
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col md:flex-row">
                {{-- Cover Image Column --}}
                <div class="w-full md:w-[320px] shrink-0 relative bg-gray-50 flex items-center justify-center">
                    @if($community->cover_image)
                        <img src="{{ asset('storage/' . $community->cover_image) }}" alt="{{ $community->name }}" class="w-full h-full min-h-[200px] object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center py-12 px-6 text-center text-gray-400">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-semibold">NO COVER IMAGE</p>
                            <a href="{{ route('admin.communities.edit', $community) }}" class="text-[10px] text-[#2A5C4D] font-bold hover:underline mt-1">Upload Image</a>
                        </div>
                    @endif

                    {{-- Active/Inactive Badge overlay --}}
                    <div class="absolute top-4 left-4 z-10">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm {{ $community->is_active ? 'bg-[#f0f9f5] text-[#2A5C4D]' : 'bg-gray-800 text-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $community->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
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
                                    <a href="{{ route('community.show', $community) }}" target="_blank">{{ $community->name }}</a>
                                </h3>
                                <div class="flex items-center gap-4 mt-1.5 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        {{ $community->slug }}
                                    </span>
                                    <span>·</span>
                                    <span>Created {{ $community->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            {{-- Members pill --}}
                            <div class="bg-gray-50 rounded-2xl px-4 py-2 border border-gray-100 text-center shrink-0">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">MEMBERS</p>
                                <p class="text-lg font-black text-gray-900 mt-1 leading-none">{{ number_format($community->member_count) }}</p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 leading-relaxed mt-4">
                            {{ $community->description ?? 'No description provided. Add one to explain the goals of this environmental community.' }}
                        </p>
                    </div>

                    {{-- Actions Row --}}
                    <div class="flex items-center justify-between border-t border-gray-100 pt-6 mt-6">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.communities.edit', $community) }}" class="inline-flex items-center gap-2 bg-[#2A5C4D] hover:bg-[#1e4237] text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Details
                            </a>

                            <form method="POST" action="{{ route('admin.communities.toggle-status', $community) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold transition">
                                    @if($community->is_active)
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        Deactivate
                                    @else
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Reactivate
                                    @endif
                                </button>
                            </form>
                        </div>

                        <div>
                            <form method="POST" action="{{ route('admin.communities.destroy', $community) }}" onsubmit="return confirm('Deleting a community will delete all its postings and comments. Are you absolutely sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-sm font-bold text-gray-900">No communities found</p>
                <p class="text-xs text-gray-400 mt-1">Try relaxing your search terms or filters.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($communities->hasPages())
        <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
            <span class="text-xs text-gray-500 font-medium">
                Showing {{ $communities->firstItem() }} to {{ $communities->lastItem() }} of {{ $communities->total() }} communities
            </span>
            <div>
                {{ $communities->links() }}
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
