<x-guest-layout>
    <x-auth-session-status :status="session('status')" />

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="color: #1e293b;">Two-Factor Authentication</h4>
        <p class="text-muted small mb-0">Open your Google Authenticator app and enter the 6-digit code for
            <strong>E-SISWI</strong>.
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger text-center small mb-3">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="one_time_password" :value="__('Authentication Code')" />
            <x-text-input id="one_time_password" type="text" name="one_time_password" inputmode="numeric"
                autocomplete="one-time-code" autofocus placeholder="000000" maxlength="6"
                style="letter-spacing: 0.5em; font-size: 1.4rem; text-align: center; font-weight: 700;"
                class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('one_time_password')" class="mt-2" />
        </div>

        <div class="d-grid mb-3">
            <x-primary-button class="justify-content-center">
                {{ __('Verify Code') }}
            </x-primary-button>
        </div>

        <p class="text-center text-muted small mb-0">
            Can't access your authenticator?
            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #6366f1;">Go back to login</a>
        </p>
    </form>
</x-guest-layout>