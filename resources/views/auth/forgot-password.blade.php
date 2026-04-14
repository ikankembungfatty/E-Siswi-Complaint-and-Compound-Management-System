<x-guest-layout>
    <h4 class="fw-bold text-center mb-1" style="color: #1e293b;">Forgot Password</h4>
    <p class="text-center text-muted small mb-4">
        {{ __('No problem. Just enter your email address and we will send you a password reset link.') }}
    </p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end mt-4">
            <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>