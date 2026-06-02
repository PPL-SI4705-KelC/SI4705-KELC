<x-guest-layout>
    <x-slot name="title">Register</x-slot>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-content">Create account</h1>
        <p class="text-sm text-content-muted mt-1">Join Act4Climate and start tracking your impact</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus class="form-input" placeholder="johndoe">
            <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="relative flex items-center">
                <input id="password" type="password" name="password" required class="form-input pr-12" placeholder="Min. 8 characters">
                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-0 inset-y-0 px-3.5 flex items-center justify-center text-gray-400 hover:text-[#2A5C4D] transition focus:outline-none bg-transparent border-0 cursor-pointer">
                    <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                        <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                        <line x1="2" y1="2" x2="22" y2="22"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="relative flex items-center">
                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input pr-12" placeholder="••••••••">
                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-0 inset-y-0 px-3.5 flex items-center justify-center text-gray-400 hover:text-[#2A5C4D] transition focus:outline-none bg-transparent border-0 cursor-pointer">
                    <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                        <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                        <line x1="2" y1="2" x2="22" y2="22"/>
                    </svg>
                </button>
            </div>
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

    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeIcon = button.querySelector('.eye-icon');
            const eyeOffIcon = button.querySelector('.eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
