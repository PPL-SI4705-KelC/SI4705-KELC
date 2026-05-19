<x-app-layout>
    <x-slot name="title">Manage Users</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">User Management</h1>
        <form class="flex gap-2"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="form-input py-2 text-sm w-64"><button class="btn-primary text-sm">Search</button></form>
    </x-slot>

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
                </tr></thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-content">{{ $u->name }}</td>
                        <td class="py-3 px-4 text-content-muted">{{ $u->email }}</td>
                        <td class="py-3 px-4 text-center"><span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-secondary' }}">{{ $u->role }}</span></td>
                        <td class="py-3 px-4 text-center">{{ $u->level }}</td>
                        <td class="py-3 px-4 text-right font-medium">{{ number_format($u->xp) }}</td>
                        <td class="py-3 px-4 text-right text-content-muted">{{ $u->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</x-app-layout>
