<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h4 class="fw-800 mb-0" style="color: var(--text-primary);">Account Settings</h4>
                <p class="mb-0 mt-1" style="color: var(--text-muted); font-size:.85rem;">
                    Manage your profile information and security settings.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="d-flex align-items-center gap-2 mb-4 px-3 py-2 rounded-3"
                style="background:#d1fae5; color:#065f46; border-left:4px solid #22c55e;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            {{-- Profile Information --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h5 class="content-card-title mb-1">Profile Information</h5>
                        <p class="text-muted small mb-0">Update your account's profile information and email address.
                        </p>
                    </div>
                    <div class="content-card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h5 class="content-card-title mb-1">Update Password</h5>
                        <p class="text-muted small mb-0">Ensure your account is using a long, random password to stay
                            secure.</p>
                    </div>
                    <div class="content-card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Two Factor Authentication --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="content-card-title mb-1"><i class="bi bi-shield-lock me-2"
                                    style="color: var(--purple-main);"></i>Two-Factor Authentication</h5>
                            <p class="text-muted small mb-0">Add an extra layer of security to your account.</p>
                        </div>
                        <a href="{{ route('two-factor.setup') }}" class="btn-outline-purple"
                            style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: var(--radius-sm);">
                            @if(Auth::user()->hasTwoFactorEnabled())
                                <i class="bi bi-gear me-1"></i> Manage
                            @else
                                <i class="bi bi-plus me-1"></i> Enable
                            @endif
                        </a>
                    </div>
                    <div class="content-card-body p-4">
                        @if(Auth::user()->hasTwoFactorEnabled())
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">
                                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                                <span class="fw-500" style="font-size:0.9rem;">Enabled. Your account is protected.</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b;">
                                <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
                                <span class="fw-500" style="font-size:0.9rem;">Disabled. We recommend enabling 2FA.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Browser Sessions --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h5 class="content-card-title mb-1">Browser Sessions</h5>
                        <p class="text-muted small mb-0">Manage and log out your active sessions on other browsers and
                            devices.</p>
                    </div>
                    <div class="content-card-body p-4">
                        @include('profile.partials.browser-sessions', ['sessions' => $sessions ?? []])
                    </div>
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="col-12">
                <div class="content-card border-danger" style="border-width: 1px;">
                    <div class="content-card-header">
                        <h5 class="content-card-title text-danger mb-1">Delete Account</h5>
                        <p class="text-muted small mb-0">Once your account is deleted, all of its resources and data
                            will be permanently deleted.</p>
                    </div>
                    <div class="content-card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>