<x-app-layout>
    <x-slot name="title">Manage Users</x-slot>
    <x-slot name="header">
<<<<<<< HEAD
        <h1 class="text-xl font-bold text-content">User Management</h1>
        <form class="flex gap-2"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="form-input py-2 text-sm w-64"><button class="btn-primary text-sm">Search</button></form>
    </x-slot>

=======
        <div>
            <h1 class="text-xl font-bold text-content">User Management 👥</h1>
            <p class="text-sm text-content-muted">View, create, update, or delete user accounts</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="form-input py-2 text-sm w-64">
                <button type="submit" class="btn-primary text-sm">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.users') }}" class="btn-ghost text-sm px-3 flex items-center justify-center border border-gray-200">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm py-2.5 px-4 shadow-md shadow-primary/20 hover:scale-105 transition-transform flex items-center gap-1.5 shrink-0">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add User
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 bg-[#2D5A4C] text-white text-sm font-bold rounded-full shadow-lg flex items-center gap-2" id="flash-success">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 bg-red-500 text-white text-sm font-bold rounded-full shadow-lg flex items-center gap-2" id="flash-error">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
    <div class="card animate-fade-in overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-surface-border">
                    <th class="text-left py-3 px-4 text-content-muted font-medium">User</th>
                    <th class="text-left py-3 px-4 text-content-muted font-medium">Email</th>
                    <th class="text-center py-3 px-4 text-content-muted font-medium">Role</th>
                    <th class="text-center py-3 px-4 text-content-muted font-medium">Level</th>
                    <th class="text-right py-3 px-4 text-content-muted font-medium">XP</th>
                    <th class="text-right py-3 px-4 text-content-muted font-medium">Joined</th>
<<<<<<< HEAD
=======
                    <th class="text-center py-3 px-4 text-content-muted font-medium">Actions</th>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
                </tr></thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50">
<<<<<<< HEAD
                        <td class="py-3 px-4 font-medium text-content">{{ $u->name }}</td>
=======
                        <td class="py-3 px-4">
                            <div class="font-medium text-content">{{ $u->name }}</div>
                            <div class="text-xs text-content-muted">{{ '@' . $u->username }}</div>
                        </td>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
                        <td class="py-3 px-4 text-content-muted">{{ $u->email }}</td>
                        <td class="py-3 px-4 text-center"><span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-secondary' }}">{{ $u->role }}</span></td>
                        <td class="py-3 px-4 text-center">{{ $u->level }}</td>
                        <td class="py-3 px-4 text-right font-medium">{{ number_format($u->xp) }}</td>
                        <td class="py-3 px-4 text-right text-content-muted">{{ $u->created_at->format('d M Y') }}</td>
<<<<<<< HEAD
=======
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-3 select-none">
                                <a href="{{ route('admin.users.edit', $u) }}" class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 hover:bg-gray-100 rounded-lg" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                </a>
                                @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Are you sure you want to delete user {{ $u->name }}? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 hover:bg-red-50/50 rounded-lg" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-content-muted italic px-2">You</span>
                                @endif
                            </div>
                        </td>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
<<<<<<< HEAD
        {{ $users->links() }}
=======
        <div class="mt-4 p-4">
            {{ $users->links() }}
        </div>
>>>>>>> cd4c856d7a25e5e37ad3c3c09980994b7ef4b0ba
    </div>
</x-app-layout>
