<x-guest-layout>
    <x-slot name="title">Login</x-slot>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-content">Welcome back</h1>
        <p class="text-sm text-content-muted mt-1">Login to continue your climate journey</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-input" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-input" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-surface-border text-primary focus:ring-primary-300">
                <span class="text-sm text-content-body">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-600 font-medium">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Login
        </button>

        <p class="text-center text-sm text-content-muted">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:text-primary-600">Sign up</a>
        </p>
    </form>
</x-guest-layout>
