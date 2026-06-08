<x-app-layout>
    <x-slot name="header">
<<<<<<< HEAD
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Community') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="w-full mx-auto sm:px-2 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Welcome to the Community section!") }}
                </div>
            </div>
        </div>
=======
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
>>>>>>> ac7a16f12a0ab597fb817dc8f456037e0ba9679f
    </div>
</x-app-layout>
