<x-app-layout>
    <x-slot name="title">Create User</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Add New User 👤</h1>
            <p class="text-sm text-content-muted">Register a new administrator or user account</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto animate-fade-in">
        <div class="card">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="form-label">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required class="form-input" placeholder="e.g. johndoe">
                        <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="e.g. johndoe@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required class="form-input" placeholder="Minimum 8 characters">
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-input">
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="level" class="form-label">Level</label>
                        <input id="level" type="number" name="level" value="{{ old('level', 1) }}" min="1" required class="form-input">
                        <x-input-error :messages="$errors->get('level')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="xp" class="form-label">XP (Experience Points)</label>
                        <input id="xp" type="number" name="xp" value="{{ old('xp', 0) }}" min="0" required class="form-input">
                        <x-input-error :messages="$errors->get('xp')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="bio" class="form-label">Bio (Optional)</label>
                    <textarea id="bio" name="bio" rows="4" class="form-input" placeholder="Brief description about the user...">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-1.5" />
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-primary py-2.5 px-6">Create User</button>
                    <a href="{{ route('admin.users') }}" class="btn-ghost py-2.5 px-5 border border-gray-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
