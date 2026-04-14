<x-guest-layout>
    <!-- Header -->
    <div class="mb-4 pb-2">
        <h3 class="fw-800 mb-2" style="color: #1e293b;">Create Account</h3>
        <p class="text-muted" style="font-size: 0.95rem;">Join E-SISWI to manage your hostel easily.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="auth-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="form-control auth-input" placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-3">
            <label for="email" class="auth-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="form-control auth-input" placeholder="KLXXXXXX@student.uptm.edu.my">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="auth-label">Password</label>
            <div class="position-relative">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="form-control auth-input pe-5" placeholder="••••••••">
                <button type="button"
                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 text-muted me-2"
                    onclick="togglePassword('password', this)" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="auth-label">Confirm Password</label>
            <div class="position-relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" class="form-control auth-input pe-5" placeholder="••••••••">
                <button type="button"
                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 text-muted me-2"
                    onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
        </div>

        <button type="submit" class="auth-btn">
            Create Account <i class="bi bi-person-plus"></i>
        </button>

        <p class="text-center mt-4 text-muted" style="font-size: 0.9rem;">
            Already registered?
            <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--purple); font-weight: 600;">
                Sign in
            </a>
        </p>
    </form>
</x-guest-layout>