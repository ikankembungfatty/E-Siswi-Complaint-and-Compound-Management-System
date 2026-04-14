<x-guest-layout>
    <h4 class="fw-bold text-center mb-1" style="color: #1e293b;">Verify Email</h4>
    <p class="text-center text-muted small mb-4">
        {{ __('Thanks for signing up! Please verify your email address by clicking the link we sent you.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-custom-success small mb-4">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="btn btn-link btn-sm text-muted text-decoration-none small">{{ __('Log Out') }}</button>
        </form>
    </div>
</x-guest-layout>