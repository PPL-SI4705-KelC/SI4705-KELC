<x-app-layout>
    <x-slot name="title">Manage Blogs</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">Blog Management</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.blogs', ['status' => 'pending']) }}" class="badge {{ $status === 'pending' ? 'badge-accent' : '' }} px-3 py-1.5 text-sm">Pending</a>
            <a href="{{ route('admin.blogs', ['status' => 'approved']) }}" class="badge {{ $status === 'approved' ? 'badge-secondary' : '' }} px-3 py-1.5 text-sm">Approved</a>
            <a href="{{ route('admin.blogs', ['status' => 'rejected']) }}" class="badge {{ $status === 'rejected' ? 'badge-danger' : '' }} px-3 py-1.5 text-sm">Rejected</a>
        </div>
    </x-slot>

<<<<<<< HEAD
    <div class="space-y-6 animate-fade-in pb-12" x-data="{ openRejectModal: false, rejectId: null, rejectTitle: '' }">

        {{-- ══ Flash Messages ══════════════════════════════════════ --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 bg-[#2D5A4C] text-white text-sm font-bold rounded-full shadow-lg flex items-center gap-2" id="flash-success">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ session('success') }}
=======
    <div class="space-y-4 animate-fade-in">
        @forelse($blogs as $blog)
        <div class="card">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-semibold text-content">{{ $blog->title }}</h3>
                    <p class="text-sm text-content-muted mt-1">By {{ $blog->user->name }} · {{ $blog->created_at->format('d M Y') }}</p>
                    <p class="text-sm text-content-body mt-2 line-clamp-2">{{ Str::limit(strip_tags($blog->content), 200) }}</p>
                </div>
>>>>>>> ac7a16f12a0ab597fb817dc8f456037e0ba9679f
            </div>
            @if($status === 'pending')
            <div class="flex gap-2 mt-4 pt-4 border-t border-surface-border">
                <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">@csrf
                    <button class="btn-secondary text-sm">✓ Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.blogs.reject', $blog) }}" class="flex-1 flex gap-2">@csrf
                    <input type="text" name="reason" required placeholder="Rejection reason..." class="form-input flex-1 py-2 text-sm">
                    <button class="btn-ghost text-sm text-red-500">✕ Reject</button>
                </form>
            </div>
<<<<<<< HEAD
        @endif

        {{-- ══ Search & Tabs ══════════════════════════════════════ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-150/70 pb-px mb-6">
            {{-- Tabs --}}
            <div class="flex items-center gap-6 text-sm" id="blog-tabs">
                <a href="{{ route('admin.blogs.index', ['tab' => 'all', 'search' => $search]) }}" id="tab-all"
                   class="pb-3.5 flex items-center gap-2 font-bold transition-all border-b-2 {{ $tab === 'all' ? 'border-[#2D5A4C] text-[#2D5A4C]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    All Blogs
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#e2f0ea] text-[#2D5A4C] leading-none select-none">{{ $allCount }}</span>
                </a>
                <a href="{{ route('admin.blogs.index', ['tab' => 'published', 'search' => $search]) }}" id="tab-published"
                   class="pb-3.5 flex items-center gap-2 font-bold transition-all border-b-2 {{ $tab === 'published' ? 'border-[#2D5A4C] text-[#2D5A4C]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    Published
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#e2f0ea] text-[#2D5A4C] leading-none select-none">{{ $publishedCount }}</span>
                </a>
                <a href="{{ route('admin.blogs.index', ['tab' => 'draft', 'search' => $search]) }}" id="tab-draft"
                   class="pb-3.5 flex items-center gap-2 font-bold transition-all border-b-2 {{ $tab === 'draft' ? 'border-[#2D5A4C] text-[#2D5A4C]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    Draft
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#fff8e1] text-[#E65100] leading-none select-none">{{ $draftCount }}</span>
                </a>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex gap-2 w-full max-w-sm mb-3 md:mb-0" id="blog-search-form">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search title, content, or tags..." class="form-input py-2 text-xs" id="search-input">
                <button type="submit" class="btn-primary text-xs px-4" id="btn-search">Search</button>
                @if($search)
                    <a href="{{ route('admin.blogs.index', ['tab' => $tab]) }}" class="btn-ghost text-xs px-2.5 flex items-center justify-center border border-gray-200" id="btn-reset-search">Reset</a>
                @endif
            </form>
        </div>

        {{-- ══ Admin Blog Posts List ══════════════════════════════ --}}
        <div class="space-y-4" id="blog-list">
            @forelse($blogs as $blog)
            <div class="card p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 group hover:border-[#2D5A4C]/20" id="blog-card-{{ $blog->id }}">
                <div class="flex-1 space-y-2">
                    <h3 class="text-[17px] font-extrabold text-gray-900 leading-snug hover:text-primary transition-colors">
                        <a href="{{ route('blogs.show', $blog) }}" target="_blank">{{ $blog->title }}</a>
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-medium">
                        {{ $blog->short_description ?? Str::limit(strip_tags($blog->content ?? ''), 120) }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-1">
                        {{-- Date --}}
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-gray-400 font-bold tracking-tight">
                            {{ $blog->created_at->format('M d, Y') }}
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </span>
                        {{-- Category --}}
                        @if($blog->category)
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-gray-400 font-bold tracking-tight">
                            {{ $blog->category }}
                        </span>
                        @endif
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $blog->status === 'published' ? 'bg-[#e2f0ea] text-[#2D5A4C]' : 'bg-[#fff8e1] text-[#E65100]' }}">
                            {{ ucfirst($blog->status) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 select-none">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-gray-400 hover:text-gray-600 transition-colors p-2.5 hover:bg-gray-50 rounded-lg" id="btn-edit-{{ $blog->id }}" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this article? This action cannot be undone.')" id="form-delete-{{ $blog->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2.5 hover:bg-red-50/50 rounded-lg" id="btn-delete-{{ $blog->id }}" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="card p-8 text-center" id="no-blogs-message">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5-3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p class="text-gray-400 font-medium text-sm">No {{ $tab === 'all' ? '' : $tab }} blogs found.</p>
                    <a href="{{ route('admin.blogs.create') }}" class="text-[#2D5A4C] font-bold text-sm hover:underline">Create your first blog →</a>
                </div>
            </div>
            @endforelse

            <div class="pt-2">
                {{ $blogs->links() }}
            </div>
        </div>

        {{-- ══ User Submissions Section ══════════════════════════ --}}
        <div class="flex items-center justify-between mt-12 mb-6" id="user-submissions-header">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">User Submissions</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $pendingCount > 0 ? 'bg-[#ffe8e8] text-[#c2410c]' : 'bg-gray-100 text-gray-500' }}">
                {{ $pendingCount }} Pending Review
            </span>
        </div>

        <div class="space-y-4" id="pending-submissions">
            @forelse($pendingBlogs as $pending)
            <div class="card p-6 flex flex-col gap-4 border border-gray-150/70 shadow-[0_2px_15px_rgba(0,0,0,0.01)]" id="pending-card-{{ $pending->id }}">
                {{-- User Profile Header --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden border border-white shadow-sm shrink-0 bg-[#e2f0ea] flex items-center justify-center">
                            @if($pending->user && $pending->user->avatar)
                                <img src="{{ asset('storage/' . $pending->user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-[#2D5A4C] text-xs font-bold">{{ strtoupper(substr($pending->user->name ?? 'U', 0, 2)) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-gray-900 leading-snug">{{ $pending->user->name }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $pending->user->bio ?? 'Community Member' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-[10px] font-black bg-[#fff8e1] text-[#E65100] uppercase tracking-wider select-none">
                        Pending
                    </span>
                </div>

                {{-- Submission Content --}}
                <div class="space-y-2">
                    <h4 class="text-base font-extrabold text-gray-900 leading-snug">{{ $pending->title }}</h4>
                    <p class="text-xs text-gray-500 leading-relaxed font-medium">
                        {{ $pending->short_description ?? Str::limit(strip_tags($pending->content ?? ''), 240) }}
                    </p>
                </div>

                {{-- Actions & Info --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-gray-100/70">
                    <div class="flex items-center gap-4 text-xs text-gray-400 font-bold">
                        {{-- Date --}}
                        <span class="inline-flex items-center gap-1.5">
                            Submitted {{ $pending->created_at->format('M d, Y') }}
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </span>
                        {{-- Word Count --}}
                        <span class="inline-flex items-center gap-1.5">
                            {{ number_format(str_word_count(strip_tags($pending->content ?? ''))) }} words
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5-3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </span>
                        {{-- Category --}}
                        @if($pending->category)
                        <span class="inline-flex items-center gap-1.5">
                            {{ $pending->category }}
                        </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 select-none">
                        {{-- Approve --}}
                        <form method="POST" action="{{ route('admin.blogs.approve', $pending) }}" class="inline" id="form-approve-{{ $pending->id }}">
                            @csrf
                            <button type="submit" class="h-10 px-5 bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-xs rounded-full flex items-center justify-center gap-1.5 shadow-sm transition-all duration-200 active:scale-[0.97] hover:shadow-md" id="btn-approve-{{ $pending->id }}">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                                Approve
                            </button>
                        </form>
                        {{-- Reject --}}
                        <button type="button"
                                @click="openRejectModal = true; rejectId = {{ $pending->id }}; rejectTitle = '{{ addslashes($pending->title) }}'"
                                class="h-10 px-5 bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-xs rounded-full flex items-center justify-center gap-1.5 shadow-sm transition-all duration-200 active:scale-[0.97] hover:shadow-md"
                                id="btn-reject-{{ $pending->id }}">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                        {{-- Review --}}
                        <a href="{{ route('blogs.show', $pending) }}" target="_blank"
                           class="h-10 px-5 bg-white border border-gray-200 text-[#1E293B] font-bold text-xs rounded-full flex items-center justify-center gap-1.5 shadow-sm hover:bg-gray-50 transition-all duration-200 active:scale-[0.97] hover:shadow-md"
                           id="btn-review-{{ $pending->id }}">
                            <svg class="w-4 h-4 text-[#1E293B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Review
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="card p-8 text-center text-gray-400" id="no-pending-message">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">No pending user submissions. All caught up! 🎉</p>
                </div>
            </div>
            @endforelse
        </div>

    {{-- ══ Alpine Rejection Modal ══════════════════════════════ --}}
    <div x-show="openRejectModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         id="reject-modal">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity"
                 x-show="openRejectModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="openRejectModal = false"></div>

            {{-- Modal Panel --}}
            <form :action="'/admin/blogs/' + rejectId + '/reject'"
                  method="POST"
                  x-show="openRejectModal"
                  x-transition:enter="ease-out duration-300"
                  x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                  x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                  x-transition:leave="ease-in duration-200"
                  x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                  x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                  class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 z-10"
                  id="reject-form">
                @csrf
                <div class="bg-white px-6 pb-6 pt-8 sm:p-8 sm:pb-6">
                    {{-- Warning Icon --}}
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-1 text-center">Reject User Submission</h3>
                    <p class="text-xs text-gray-500 mb-5 text-center">
                        Please provide a constructive reason explaining why <span class="font-bold text-gray-700" x-text="rejectTitle"></span> is rejected so the author can improve it.
                    </p>

                    <label for="reject_reason" class="form-label">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reject_reason"
                              name="reject_reason"
                              rows="4"
                              required
                              minlength="5"
                              maxlength="500"
                              class="form-input"
                              placeholder="e.g. Please add more references regarding the carbon reduction statistics."></textarea>
                    <p class="text-[10px] text-gray-400 mt-1.5">Minimum 5 characters, maximum 500 characters.</p>

                    @error('reject_reason')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 border-t border-gray-100 gap-2 select-none">
                    <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-red-500 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-red-600 transition sm:w-auto"
                            id="btn-submit-reject">
                        Reject Submission
                    </button>
                    <button type="button"
                            @click="openRejectModal = false"
                            class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-xs font-bold text-gray-700 shadow-sm border border-gray-200 hover:bg-gray-50 transition sm:w-auto"
                            id="btn-cancel-reject">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
=======
            @endif
>>>>>>> ac7a16f12a0ab597fb817dc8f456037e0ba9679f
        </div>
        @empty
        <div class="text-center py-12"><p class="text-content-muted">No {{ $status }} blogs.</p></div>
        @endforelse
        {{ $blogs->links() }}
    </div>
</x-app-layout>
