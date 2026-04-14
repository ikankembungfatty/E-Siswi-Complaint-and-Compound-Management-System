<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Warden Greeting Banner --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="esiswi-banner position-relative overflow-hidden">
                    <div class="position-relative z-1">
                        <h3 class="fw-800 text-white mb-2" style="font-size: 1.75rem;">Welcome back,
                            {{ Auth::user()->name }}</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Manage hostel complaints and compound
                            issuances here.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid (4 cards) --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="border-top: 4px solid #f59e0b;">
                    <div class="stat-label">Processing Complaints</div>
                    <div class="stat-value">{{ $stats['processing_complaints'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="border-top: 4px solid var(--purple-main);">
                    <div class="stat-label">My Assigned Tasks</div>
                    <div class="stat-value" style="color: var(--purple-main);">{{ $stats['my_assigned'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="border-top: 4px solid #10b981;">
                    <div class="stat-label">Completed Today</div>
                    <div class="stat-value">{{ $stats['completed_today'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="border-top: 4px solid #ef4444;">
                    <div class="stat-label">Compounds Issued</div>
                    <div class="stat-value" style="color: #ef4444;">{{ $stats['compounds_issued'] }}</div>
                </div>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="row g-4">
            {{-- Processing / Pending Complaints --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header d-flex justify-content-between align-items-center">
                        <h5 class="content-card-title mb-0">Processing Complaints</h5>
                        <a href="{{ route('complaints.index') }}" class="btn-outline-purple"
                            style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">View All</a>
                    </div>
                    <div class="content-card-body p-3">
                        @forelse($pending_complaints as $complaint)
                            <div class="d-flex justify-content-between align-items-start p-3 mb-2 flex-wrap gap-2 rounded border"
                                style="background: var(--bg-main); transition: transform 0.2s; cursor: pointer;"
                                onclick="window.location='{{ route('complaints.show', $complaint) }}'"
                                onmouseover="this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.transform='none'">
                                <div>
                                    <div class="fw-600" style="color: var(--text-primary); font-size: 0.9rem;">
                                        {{ $complaint->title }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                        {{ $complaint->user->name }} — {{ $complaint->category->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;"><i
                                            class="bi bi-clock me-1"></i>{{ $complaint->created_at->diffForHumans() }}</div>
                                </div>
                                @php
                                    $pCls = match ($complaint->priority) {
                                        'high' => 'background:#fef3c7; color:#b45309;',
                                        'low' => 'background:#f0fdf4; color:#16a34a;',
                                        default => 'background:#eff6ff; color:#2563eb;',
                                    };
                                @endphp
                                <span class="status-badge" style="{{ $pCls }}">{{ ucfirst($complaint->priority) }}</span>
                            </div>
                        @empty
                            <div class="empty-state py-5"><i class="bi bi-emoji-smile d-block text-muted mb-2"></i>
                                <p class="mb-0">No pending complaints.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- My Tasks --}}
            <div class="col-lg-6">
                <div class="content-card h-100">
                    <div class="content-card-header d-flex justify-content-between align-items-center">
                        <h5 class="content-card-title mb-0">My Tasks</h5>
                        <a href="{{ route('compounds.create') }}" class="btn"
                            style="background:#ef4444; color:white; padding: 0.35rem 0.8rem; font-size: 0.8rem; font-weight: 600; border-radius: 8px;">
                            <i class="bi bi-plus me-1"></i> Issue Compound
                        </a>
                    </div>
                    <div class="content-card-body p-3">
                        @forelse($my_complaints as $complaint)
                            <div class="d-flex justify-content-between align-items-start p-3 mb-2 flex-wrap gap-2 rounded border"
                                style="background: var(--bg-main); transition: transform 0.2s; cursor: pointer;"
                                onclick="window.location='{{ route('complaints.show', $complaint) }}'"
                                onmouseover="this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.transform='none'">
                                <div>
                                    <div class="fw-600" style="color: var(--text-primary); font-size: 0.9rem;">
                                        {{ $complaint->title }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                        {{ $complaint->user->name }} — {{ $complaint->location ?? 'No location specified' }}
                                    </div>
                                </div>
                                @php
                                    $sCls = match ($complaint->status) {
                                        'completed' => 'status-completed',
                                        'processing' => 'status-processing',
                                        'in_progress' => 'status-processing',
                                        'rejected' => 'status-rejected',
                                        default => 'status-pending',
                                    };
                                @endphp
                                <span
                                    class="status-badge {{ $sCls }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span>
                            </div>
                        @empty
                            <div class="empty-state py-5">
                                <p class="mb-0">No assigned tasks.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>