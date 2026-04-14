<section>
    <header class="mb-4">
        <h5 class="fw-semibold mb-1" style="color: #1e293b;">
            <i class="bi bi-display me-2" style="color: #6366f1;"></i>Browser Sessions
        </h5>
        <p class="text-muted small mb-0">
            Manage and terminate active sessions on other browsers and devices.
        </p>
    </header>

    {{-- Session List --}}
    <div class="mb-3">
        @forelse($sessions as $session)
            <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                {{-- Device Icon --}}
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width: 44px; height: 44px; background: {{ $session->is_current_device ? '#ede9fe' : '#f1f5f9' }};">
                    <i class="{{ $session->agent->icon }} fs-5"
                        style="color: {{ $session->is_current_device ? '#6366f1' : '#64748b' }};"></i>
                </div>

                {{-- Session Info --}}
                <div class="flex-grow-1 min-width-0">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                        <span class="fw-semibold small" style="color: #1e293b;">
                            {{ $session->agent->platform }} — {{ $session->agent->browser }}
                        </span>
                        @if($session->is_current_device)
                            <span class="badge rounded-pill" style="background: #ede9fe; color: #6366f1; font-size: 0.7rem;">
                                <i class="bi bi-check-circle-fill me-1"></i>This Device
                            </span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="text-muted" style="font-size: 0.78rem;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $session->ip_address ?? 'Unknown IP' }}
                        </span>
                        <span title="{{ $session->last_active_exact }}" class="text-muted"
                            style="font-size: 0.78rem; cursor: help;">
                            <i class="bi bi-clock me-1"></i>
                            {{ $session->is_current_device ? 'Active now' : 'Last active ' . $session->last_active }}
                        </span>
                    </div>
                </div>

                {{-- Terminate Button (only for non-current sessions) --}}
                @if(!$session->is_current_device)
                    <button type="button" class="btn btn-sm flex-shrink-0"
                        style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; font-size: 0.8rem; padding: 5px 12px; white-space: nowrap;"
                        onclick="openTerminateModal('{{ $session->id }}', '{{ $session->agent->platform }} — {{ $session->agent->browser }}', '{{ $session->ip_address }}')">
                        <i class="bi bi-x-circle me-1"></i>Terminate
                    </button>
                @endif
            </div>
        @empty
            <p class="text-muted small">No active sessions found.</p>
        @endforelse
    </div>

    {{-- Status when only one session --}}
    @if(count(array_filter($sessions, fn($s) => !$s->is_current_device)) === 0)
        <div class="info-panel info-panel-green mt-2">
            <p class="mb-0 small" style="color: #065f46;">
                <i class="bi bi-shield-check me-1"></i>
                No other active sessions. You are only logged in on this device.
            </p>
        </div>
    @endif
</section>

{{-- ===== Terminate Session Modal ===== --}}
<div class="modal fade" id="terminateSessionModal" tabindex="-1" aria-labelledby="terminateSessionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 40px; height:40px; background: #fee2e2;">
                        <i class="bi bi-shield-exclamation" style="color: #dc2626; font-size: 1.1rem;"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0" id="terminateSessionModalLabel" style="color: #1e293b;">
                        Terminate Session
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pt-3 pb-2">
                <p class="text-muted small mb-3">
                    You are about to terminate the session for:
                </p>
                <div class="p-3 rounded-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fw-semibold small" id="modal-session-label" style="color: #1e293b;"></div>
                    <div class="text-muted" style="font-size: 0.78rem;" id="modal-session-ip"></div>
                </div>

                <form id="terminate-session-form" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-1">
                        <x-input-label for="terminate_password" :value="__('Enter Your Password to Confirm')" />
                        <x-text-input id="terminate_password" name="password" type="password"
                            autocomplete="current-password" placeholder="Your current password" class="mt-1" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 18px;">
                    Cancel
                </button>
                <button type="button" class="btn btn-sm" id="confirm-terminate-btn"
                    style="background: #dc2626; color: white; border: none; border-radius: 8px; padding: 8px 18px;"
                    onclick="document.getElementById('terminate-session-form').submit()">
                    <i class="bi bi-x-circle me-1"></i>Terminate Session
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openTerminateModal(sessionId, label, ip) {
        // Set the form action dynamically
        const baseUrl = '{{ url("/profile/sessions") }}';
        document.getElementById('terminate-session-form').action = baseUrl + '/' + sessionId;

        // Populate modal details
        document.getElementById('modal-session-label').textContent = label;
        document.getElementById('modal-session-ip').textContent = '📍 ' + ip;

        // Clear password field every time
        document.getElementById('terminate_password').value = '';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('terminateSessionModal'));
        modal.show();

        // Focus password field after modal shows
        document.getElementById('terminateSessionModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('terminate_password').focus();
        }, { once: true });
    }
</script>