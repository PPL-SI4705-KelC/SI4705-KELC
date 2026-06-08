<x-app-layout>
    <x-slot name="title">Blogs</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-content">Climate Blog</h1>
            <p class="text-base text-content-muted mt-1">Read and share climate stories</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('blogs.my') }}" class="btn-outline text-sm">My Blogs</a>
            <a href="{{ route('blogs.create') }}" class="btn-primary text-sm">Write Blog</a>
        </div>
    </x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in">
        <form action="{{ route('blogs.index') }}" method="GET" class="w-full md:max-w-md relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search stories, topics, authors, or tags..." 
                   class="form-input pl-10 bg-white border-gray-200 focus:border-[#2D5A4C] focus:ring-[#2D5A4C]/20 shadow-sm rounded-xl w-full">
            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            @if(request('search'))
                <a href="{{ route('blogs.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            @endif
        </form>
    </div>

    @if(request('search'))
        <p class="text-sm text-gray-500 mb-6 animate-fade-in">Showing results for <span class="font-bold text-gray-900">"{{ request('search') }}"</span></p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
        @forelse($blogs as $blog)
        <a href="{{ route('blogs.show', $blog) }}" class="card group hover:shadow-elevated transition-all duration-300">
            @if($blog->featured_image)
            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" class="w-full h-40 object-cover rounded-xl mb-4">
            @else
            <div class="w-full h-40 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-xl mb-4 flex items-center justify-center">
                <span class="text-4xl">🌍</span>
            </div>
            @endif
            <h3 class="font-semibold text-content group-hover:text-primary transition-colors line-clamp-2 break-words">{{ $blog->title }}</h3>
            <p class="text-sm text-content-muted mt-2 line-clamp-2 break-words">{{ $blog->short_description ?? Str::limit(strip_tags($blog->content), 120) }}</p>
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-surface-border">
                @if($blog->user->avatar)
                    <img src="{{ asset('storage/' . $blog->user->avatar) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover">
                @else
                    <div class="avatar-primary text-xs w-7 h-7">{{ substr($blog->user->name, 0, 2) }}</div>
                @endif
                <div>
                    <p class="text-xs font-medium text-content">{{ $blog->user->name }}</p>
                    <p class="text-[10px] text-content-muted">{{ $blog->created_at?->diffForHumans() }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-16">
            <span class="text-5xl">📝</span>
            <p class="text-content-muted mt-4">No blogs published yet. Be the first!</p>
            <a href="{{ route('blogs.create') }}" class="btn-primary mt-4 inline-flex">Write a Blog</a>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $blogs->links() }}</div>
</x-app-layout>
