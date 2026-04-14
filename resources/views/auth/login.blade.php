<x-guest-layout>
    <!-- Header -->
    <div class="mb-4 pb-2">
        <h3 class="fw-800 mb-2" style="color: #1e293b;">Welcome Back</h3>
        <p class="text-muted" style="font-size: 0.95rem;">Please enter your details to sign in.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="auth-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" class="form-control auth-input" placeholder="KLXXXXXX@student.uptm.edu.my">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="auth-label">Password</label>
            <div class="position-relative">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="form-control auth-input pe-5" placeholder="••••••••">
                <button type="button"
                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 text-muted me-2"
                    onclick="togglePassword('password', this)" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @if (Route::has('password.request'))
                <div class="text-end mt-2">
                    <a href="{{ route('password.request') }}" class="small text-decoration-none"
                        style="color: var(--purple); font-weight: 600;">
                        Forgot password?
                    </a>
                </div>
            @endif
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="cursor:pointer;">
            <label class="form-check-label text-muted" for="remember_me" style="font-size: 0.9rem; cursor:pointer;">
                {{ __('Keep me signed in') }}
            </label>
        </div>

        <button type="submit" class="auth-btn">
            Sign In <i class="bi bi-arrow-right"></i>
        </button>

        @if (Route::has('register'))
            <p class="text-center mt-4 text-muted" style="font-size: 0.9rem;">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-decoration-none"
                    style="color: var(--purple); font-weight: 600;">
                    Sign up
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>