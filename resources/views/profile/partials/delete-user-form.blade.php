<div class="d-flex justify-content-between align-items-center">
    <p class="text-danger small mb-0 fw-500">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
    </p>

    <!-- Delete Button triggers Bootstrap Modal -->
    <button type="button" class="btn btn-sm"
        style="background:#ef4444; color:white; font-weight:600; border-radius:8px; padding:0.4rem 1rem; white-space:nowrap;"
        data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        {{ __('Delete Account') }}
    </button>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header border-0 pb-0 mt-2 px-4">
                    <h5 class="modal-title fw-semibold text-danger" id="deleteAccountModalLabel">
                        {{ __('Delete Account Confirmation') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="text-muted small">
                        {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                    <div class="mt-4">
                        <label for="delete_password" class="form-label"
                            style="font-size: 0.85rem; font-weight: 600; color: #475569;">{{ __('Password') }}</label>
                        <input id="delete_password" name="password" type="password" class="form-control"
                            style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-size: 0.95rem; background-color: #f8fafc;"
                            placeholder="{{ __('Password') }}">
                        @if($errors->userDeletion->has('password'))
                            <div class="small text-danger mt-1">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn text-muted fw-500 rounded-pill px-4"
                        style="background:#f8fafc; border:1px solid #e2e8f0;"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn"
                        style="background:#ef4444; color:white; font-weight:600; border-radius:8px; padding:0.4rem 1.2rem;">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>