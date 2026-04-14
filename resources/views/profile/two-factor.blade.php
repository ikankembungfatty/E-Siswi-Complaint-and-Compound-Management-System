<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-header-title">Two-Factor Authentication</h2>
            <a href="{{ route('profile.edit') }}" class="action-link"><i class="bi bi-arrow-left me-1"></i>Back to Profile</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 600px;">

            @if(session('success'))
                <div class="alert alert-custom-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            @if($user->hasTwoFactorEnabled())
                {{-- 2FA is already active --}}
                <div class="card-custom p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <span style="font-size: 3rem;">✅</span>
                        <h5 class="fw-bold mt-2" style="color: #065f46;">Two-Factor Authentication is Active</h5>
                        <p class="text-muted small">Your account is protected with Google Authenticator.</p>
                    </div>

                    <div class="info-panel info-panel-amber mb-4">
                        <p class="mb-0 small" style="color: #92400e;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Warning:</strong> Disabling 2FA will make your account less secure. Only do this if you are switching authenticator apps.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to disable Two-Factor Authentication?')">
                                <i class="bi bi-shield-x me-2"></i>Disable Two-Factor Authentication
                            </button>
                        </div>
                    </form>
                </div>

            @else
                {{-- 2FA Setup --}}
                <div class="card-custom p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <span style="font-size: 3rem;">📱</span>
                        <h5 class="fw-bold mt-2" style="color: #1e293b;">Set Up Google Authenticator</h5>
                        <p class="text-muted small">Scan the QR code below with your Google Authenticator app, then enter the 6-digit code to confirm and enable 2FA.</p>
                    </div>

                    {{-- Step 1: Download app --}}
                    <div class="info-panel info-panel-blue mb-4">
                        <h6 class="fw-semibold mb-2" style="color: #3730a3;"><i class="bi bi-1-circle me-2"></i>Step 1: Install the App</h6>
                        <p class="small mb-0" style="color: #475569;">
                            Download <strong>Google Authenticator</strong> on your phone from the
                            <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="text-decoration-none" style="color: #6366f1;">App Store</a>
                            or
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="text-decoration-none" style="color: #6366f1;">Google Play</a>.
                        </p>
                    </div>

                    {{-- Step 2: Scan QR code --}}
                    <div class="info-panel info-panel-blue mb-4">
                        <h6 class="fw-semibold mb-3" style="color: #3730a3;"><i class="bi bi-2-circle me-2"></i>Step 2: Scan This QR Code</h6>
                        <div class="text-center mb-3">
                            <div class="d-inline-block p-3 bg-white rounded border" style="line-height: 0;">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                        <p class="text-center small text-muted mb-2">Can't scan? Enter this key manually:</p>
                        <div class="text-center">
                            <code class="p-2 rounded d-inline-block" style="background: #f1f5f9; font-size: 1rem; letter-spacing: 0.15em; color: #1e293b;">
                                {{ wordwrap($secret, 4, ' ', true) }}
                            </code>
                        </div>
                    </div>

                    {{-- Step 3: Verify --}}
                    <div class="info-panel info-panel-blue mb-4">
                        <h6 class="fw-semibold mb-3" style="color: #3730a3;"><i class="bi bi-3-circle me-2"></i>Step 3: Verify & Enable</h6>
                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <div class="mb-3">
                                <x-input-label for="one_time_password" :value="__('Enter the 6-digit code from your app')" />
                                <x-text-input
                                    id="one_time_password"
                                    type="text"
                                    name="one_time_password"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    autofocus
                                    placeholder="000000"
                                    maxlength="6"
                                    style="letter-spacing: 0.4em; font-size: 1.2rem; text-align: center; font-weight: 600;"
                                />
                                <x-input-error :messages="$errors->get('one_time_password')" class="mt-2" />
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success-custom">
                                    <i class="bi bi-shield-check me-2"></i>Enable Two-Factor Authentication
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
