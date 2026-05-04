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

    <div class="space-y-4 animate-fade-in">
        @forelse($blogs as $blog)
        <div class="card">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-semibold text-content">{{ $blog->title }}</h3>
                    <p class="text-sm text-content-muted mt-1">By {{ $blog->user->name }} · {{ $blog->created_at->format('d M Y') }}</p>
                    <p class="text-sm text-content-body mt-2 line-clamp-2">{{ Str::limit(strip_tags($blog->content), 200) }}</p>
                </div>
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
            @endif
        </div>
        @empty
        <div class="text-center py-12"><p class="text-content-muted">No {{ $status }} blogs.</p></div>
        @endforelse
        {{ $blogs->links() }}
    </div>
</x-app-layout>
