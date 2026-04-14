<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-4">
        <label for="update_password_current_password" class="form-label"
            style="font-size: 0.85rem; font-weight: 600; color: #475569;">
            {{ __('Current Password') }}
        </label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control"
            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
            autocomplete="current-password">
        @if($errors->updatePassword->has('current_password'))
        <div class="small text-danger mt-1">{{ $errors->updatePassword->first('current_password') }}</div>@endif
    </div>

    <div class="mb-4">
        <label for="update_password_password" class="form-label"
            style="font-size: 0.85rem; font-weight: 600; color: #475569;">
            {{ __('New Password') }}
        </label>
        <input id="update_password_password" name="password" type="password" class="form-control"
            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
            autocomplete="new-password">
        @if($errors->updatePassword->has('password'))
        <div class="small text-danger mt-1">{{ $errors->updatePassword->first('password') }}</div>@endif
    </div>

    <div class="mb-4">
        <label for="update_password_password_confirmation" class="form-label"
            style="font-size: 0.85rem; font-weight: 600; color: #475569;">
            {{ __('Confirm Password') }}
        </label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
            class="form-control"
            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
            autocomplete="new-password">
        @if($errors->updatePassword->has('password_confirmation'))
            <div class="small text-danger mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
        @endif
    </div>

    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="submit" class="btn-purple" style="padding: 0.5rem 1.25rem;">{{ __('Update Password') }}</button>
        @if (session('status') === 'password-updated')
            <span class="small fw-500 text-success"><i class="bi bi-check2-circle me-1"></i>{{ __('Saved.') }}</span>
        @endif
    </div>
</form>