<x-app-layout>
    <x-slot name="title">My Blogs</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">My Blog Posts</h1>
        <a href="{{ route('blogs.create') }}" class="btn-primary text-sm">Write New</a>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        @forelse($blogs as $blog)
        <div class="card p-5 hover:shadow-card transition-shadow">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h3 class="font-bold text-[#1E293B] text-lg">{{ $blog->title }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $blog->status === 'published' ? 'bg-[#e2f0ea] text-[#2D5A4C]' : 
                              ($blog->status === 'pending' ? 'bg-[#fff8e1] text-[#E65100]' : 
                              ($blog->status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-red-50 text-red-600')) }}">
                            {{ $blog->status }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-2 text-xs font-medium text-gray-500">
                        <span>{{ $blog->created_at->format('M d, Y') }}</span>
                        @if($blog->category)
                        <span>&bull;</span>
                        <span>{{ $blog->category }}</span>
                        @endif
                    </div>
                    @if($blog->status === 'rejected' && $blog->reject_reason)
                    <div class="mt-3 p-3 bg-red-50 rounded-xl border border-red-100 text-sm text-red-700">
                        <strong>Admin Feedback:</strong> {{ $blog->reject_reason }}
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if(in_array($blog->status, ['published', 'pending']))
                    <a href="{{ route('blogs.show', $blog) }}" class="h-9 px-4 bg-white border border-gray-200 text-[#1E293B] font-bold text-xs rounded-full flex items-center justify-center hover:bg-gray-50 transition-all shadow-sm">View</a>
                    @endif
                    
                    @if(in_array($blog->status, ['draft', 'rejected']))
                    <a href="{{ route('blogs.edit', $blog) }}" class="h-9 px-4 bg-[#e2f0ea] text-[#2D5A4C] font-bold text-xs rounded-full flex items-center justify-center hover:bg-[#D1E1DB] transition-all shadow-sm">Edit</a>
                    @endif
                    
                    @if($blog->status === 'draft')
                    <form method="POST" action="{{ route('blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="h-9 px-4 bg-white border border-red-200 text-red-500 font-bold text-xs rounded-full flex items-center justify-center hover:bg-red-50 transition-all shadow-sm">Delete</button>
                    </form>
                    @endif
                </div>
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
