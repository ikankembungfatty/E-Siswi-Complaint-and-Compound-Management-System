<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-4">
        <label for="name" class="form-label"
            style="font-size: 0.85rem; font-weight: 600; color: #475569;">{{ __('Name') }}</label>
        <input id="name" name="name" type="text" class="form-control"
            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label"
            style="font-size: 0.85rem; font-weight: 600; color: #475569;">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" class="form-control"
            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
            value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')<div class="small text-danger mt-1">{{ $message }}</div>@enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="small" style="color: #475569;">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-underline small"
                        style="color: var(--purple-main);">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="small text-success mt-1 fw-500">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="submit" class="btn-purple" style="padding: 0.5rem 1.25rem;">{{ __('Save Changes') }}</button>
        @if (session('status') === 'profile-updated')
            <span class="small fw-500 text-success"><i class="bi bi-check2-circle me-1"></i>{{ __('Saved.') }}</span>
        @endif
    </div>
</form>