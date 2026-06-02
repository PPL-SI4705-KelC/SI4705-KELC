<x-app-layout>
    <x-slot name="title">Blogs</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Climate Blog</h1>
            <p class="text-sm text-content-muted">Read and share climate stories</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('blogs.my') }}" class="btn-outline text-sm">My Blogs</a>
            <a href="{{ route('blogs.create') }}" class="btn-primary text-sm">Write Blog</a>
        </div>
    </x-slot>

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
                <div class="avatar-primary text-xs w-7 h-7">{{ substr($blog->user->name, 0, 2) }}</div>
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
