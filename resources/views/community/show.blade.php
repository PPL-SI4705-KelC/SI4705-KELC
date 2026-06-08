<x-app-layout>
    <x-slot name="title">{{ $community->name }}</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">{{ $community->name }}</h1>
            <p class="text-sm text-content-muted">{{ $community->member_count }} members</p>
        </div>
        @if(!$isMember)
        <form method="POST" action="{{ route('community.join', $community) }}">@csrf <button class="btn-primary text-sm">Join Community</button></form>
        @endif
    </x-slot>

    <div class="max-w-2xl space-y-6 animate-fade-in">
        @if($isMember)
        {{-- Create Post --}}
        <div class="card">
            <form method="POST" action="{{ route('community.posts.store', $community) }}" enctype="multipart/form-data">
                @csrf
                <textarea name="content" rows="3" required class="form-input mb-3" placeholder="Share something with the community..."></textarea>
                <div class="flex justify-between items-center">
                    <input type="file" name="image" accept="image/*" class="text-sm text-content-muted">
                    <button type="submit" class="btn-primary text-sm">Post</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Posts Feed --}}
        @forelse($posts as $post)
        <div class="card">
            <div class="flex items-center gap-3 mb-3">
                <div class="avatar-primary text-xs">{{ substr($post->user->name, 0, 2) }}</div>
                <div>
                    <p class="text-sm font-semibold text-content">{{ $post->user->name }}</p>
                    <p class="text-xs text-content-muted">{{ $post->created_at->diffForHumans() }} · Lv.{{ $post->user->level }}</p>
                </div>
            </div>
            <p class="text-content-body text-sm whitespace-pre-line">{{ $post->content }}</p>
            @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="" class="w-full rounded-xl mt-3 max-h-80 object-cover">
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-4 mt-4 pt-3 border-t border-surface-border">
                <form method="POST" action="{{ route('posts.like', $post) }}">@csrf
                    <button class="flex items-center gap-1.5 text-sm {{ in_array($post->id, $likedPostIds) ? 'text-red-500' : 'text-content-muted hover:text-red-500' }} transition-colors">
                        <svg class="w-4 h-4" fill="{{ in_array($post->id, $likedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ $post->likes_count }}
                    </button>
                </form>
                <span class="flex items-center gap-1.5 text-sm text-content-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    {{ $post->comments_count }}
                </span>
                <form method="POST" action="{{ route('posts.save', $post) }}">@csrf
                    <button class="flex items-center gap-1.5 text-sm {{ in_array($post->id, $savedPostIds) ? 'text-accent-700' : 'text-content-muted hover:text-accent-700' }} transition-colors">
                        <svg class="w-4 h-4" fill="{{ in_array($post->id, $savedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        Save
                    </button>
                </form>
            </div>

            {{-- Comments --}}
            @if($post->comments->count() > 0)
            <div class="mt-3 space-y-2">
                @foreach($post->comments->take(3) as $comment)
                <div class="flex gap-2 pl-2 border-l-2 border-surface-border">
                    <div class="flex-1">
                        <p class="text-xs"><span class="font-semibold text-content">{{ $comment->user->name }}</span> <span class="text-content-muted">· {{ $comment->created_at->diffForHumans() }}</span></p>
                        <p class="text-sm text-content-body">{{ $comment->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Add Comment --}}
            @if($isMember)
            <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="mt-3 flex gap-2">
                @csrf
                <input type="text" name="content" required placeholder="Add a comment..." class="form-input flex-1 py-2 text-sm">
                <button type="submit" class="btn-ghost text-sm">Send</button>
            </form>
            @endif
        </div>
        @empty
        <div class="text-center py-12">
            <p class="text-content-muted">No posts yet. Start the conversation!</p>
        </div>
        @endforelse
        {{ $posts->links() }}
    </div>
</x-app-layout>
