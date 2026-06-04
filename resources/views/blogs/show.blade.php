<x-app-layout>
    <x-slot name="title">{{ $blog->title }}</x-slot>
    <x-slot name="header">
        <a href="{{ route('blogs.index') }}" class="text-sm text-content-muted hover:text-primary">← Back to Blogs</a>
    </x-slot>

    <article class="max-w-3xl mx-auto animate-fade-in">
        @if($blog->featured_image)
        <div class="relative mb-6">
            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" class="w-full h-64 object-cover rounded-2xl shadow-sm">
            @if($blog->tags)
            <div class="absolute top-4 right-4 flex flex-wrap justify-end gap-2 max-w-[70%]">
                @foreach(explode(',', $blog->tags) as $tag)
                    @if(trim($tag))
                        <span class="bg-black/60 backdrop-blur-md text-white px-3 py-1.5 text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm border border-white/10">
                            #{{ trim($tag) }}
                        </span>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
        @endif

        <h1 class="text-3xl font-bold text-content leading-tight">{{ $blog->title }}</h1>

        <div class="flex items-center gap-3 mt-4 pb-6 border-b border-surface-border">
            @if($blog->user->avatar)
                <img src="{{ asset('storage/' . $blog->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="avatar-primary">{{ substr($blog->user->name, 0, 2) }}</div>
            @endif
            <div>
                <p class="font-medium text-content">{{ $blog->user->name }}</p>
                <p class="text-sm text-content-muted">{{ $blog->created_at?->format('d M Y') }} · Level {{ $blog->user->level }}</p>
            </div>
        </div>

        <div class="trix-content max-w-none mt-6 text-content-body leading-relaxed break-words">
            {!! $blog->content !!}
        </div>
    </article>
</x-app-layout>
