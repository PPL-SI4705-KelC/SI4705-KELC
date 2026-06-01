<x-guest-layout>
    <x-slot name="title">Register</x-slot>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-content">Create account</h1>
        <p class="text-sm text-content-muted mt-1">Join Act4Climate and start tracking your impact</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-input" placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required class="form-input" placeholder="johndoe">
            <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required class="form-input" placeholder="Min. 8 characters">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Create Account
        </button>

        <p class="text-center text-sm text-content-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:text-primary-600">Sign in</a>
        </p>
    </form>
</x-guest-layout>
