<x-app-layout>
    <x-slot name="title">My Blogs</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">My Blog Posts</h1>
        <a href="{{ route('blogs.create') }}" class="btn-primary text-sm">Write New</a>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        @forelse($blogs as $blog)
        <div class="card flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h3 class="font-semibold text-content">{{ $blog->title }}</h3>
                    <span class="badge {{ $blog->status === 'approved' ? 'badge-secondary' : ($blog->status === 'pending' ? 'badge-accent' : 'badge-danger') }}">
                        {{ ucfirst($blog->status) }}
                    </span>
                </div>
                <p class="text-sm text-content-muted mt-1">{{ $blog->created_at->format('d M Y') }}</p>
                @if($blog->status === 'rejected' && $blog->rejection_reason)
                <p class="text-sm text-red-600 mt-1">Reason: {{ $blog->rejection_reason }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                @if($blog->status === 'approved')
                <a href="{{ route('blogs.show', $blog) }}" class="btn-ghost text-xs">View</a>
                @endif
                @if($blog->status !== 'approved')
                <a href="{{ route('blogs.edit', $blog) }}" class="btn-ghost text-xs">Edit</a>
                @endif
                <form method="POST" action="{{ route('blogs.destroy', $blog) }}" onsubmit="return confirm('Delete this blog?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ghost text-xs text-red-500">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <p class="text-content-muted">You haven't written any blogs yet.</p>
            <a href="{{ route('blogs.create') }}" class="btn-primary mt-4 inline-flex">Write Your First Blog</a>
        </div>
        @endforelse
        {{ $blogs->links() }}
    </div>
</x-app-layout>
