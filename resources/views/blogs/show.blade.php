<x-app-layout>
    <x-slot name="title">{{ $blog->title }}</x-slot>
    <x-slot name="header">
        <a href="{{ route('blogs.index') }}" class="text-sm text-content-muted hover:text-primary">← Back to Blogs</a>
    </x-slot>

    <article class="max-w-3xl mx-auto animate-fade-in">
        @if($blog->cover_image)
        <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="" class="w-full h-64 object-cover rounded-2xl mb-6">
        @endif

        <h1 class="text-3xl font-bold text-content leading-tight">{{ $blog->title }}</h1>

        <div class="flex items-center gap-3 mt-4 pb-6 border-b border-surface-border">
            <div class="avatar-primary">{{ substr($blog->user->name, 0, 2) }}</div>
            <div>
                <p class="font-medium text-content">{{ $blog->user->name }}</p>
                <p class="text-sm text-content-muted">{{ $blog->published_at?->format('d M Y') }} · Level {{ $blog->user->level }}</p>
            </div>
        </div>

        <div class="prose prose-green max-w-none mt-6 text-content-body leading-relaxed">
            {!! nl2br(e($blog->content)) !!}
        </div>
    </article>
</x-app-layout>
