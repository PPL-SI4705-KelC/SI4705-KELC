<x-app-layout>
    <x-slot name="title">Communities</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Climate Communities 🌍</h1>
            <p class="text-sm text-content-muted">Connect with eco-minded people</p>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
        @forelse($communities as $community)
        <div class="card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-secondary-100 flex items-center justify-center text-xl">🌿</div>
                <div>
                    <h3 class="font-semibold text-content">{{ $community->name }}</h3>
                    <p class="text-xs text-content-muted">{{ $community->members_count }} members</p>
                </div>
            </div>
            <p class="text-sm text-content-body line-clamp-2 mb-4">{{ $community->description ?? 'A community for climate action.' }}</p>
            <div class="flex gap-2">
                <a href="{{ route('community.show', $community) }}" class="btn-outline text-xs flex-1 justify-center">View</a>
                @if(in_array($community->id, $myCommunities))
                <form method="POST" action="{{ route('community.leave', $community) }}">
                    @csrf
                    <button class="btn-ghost text-xs text-red-500">Leave</button>
                </form>
                @else
                <form method="POST" action="{{ route('community.join', $community) }}">
                    @csrf
                    <button class="btn-secondary text-xs">Join</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <span class="text-5xl">🌱</span>
            <p class="text-content-muted mt-4">No communities yet. Stay tuned!</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $communities->links() }}</div>
</x-app-layout>
