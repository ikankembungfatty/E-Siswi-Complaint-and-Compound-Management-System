<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Welcome Banner --}}
        <div class="esiswi-banner d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
                <p>UPTM Female Hostel
                    @if(Auth::user()->student_id)
                        &bull; ID: {{ Auth::user()->student_id }}
                    @endif
                </p>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=6a5ae0&size=128"
                alt="{{ Auth::user()->name }}" class="banner-avatar d-none d-sm-block">
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-card-label">Total Complaints</div>
                        <div class="stat-card-value">{{ $stats['total_complaints'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:#eef2ff; color:#6366f1;">
                        <i class="bi bi-list-ul"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-card-label">Processing</div>
                        <div class="stat-card-value">{{ $stats['processing_complaints'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:#fff7ed; color:#f59e0b;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-card-label">Completed</div>
                        <div class="stat-card-value">{{ $stats['completed_complaints'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:#f0fdf4; color:#22c55e;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-card-label">Unpaid Fines</div>
                        <div class="stat-card-value" style="font-size:1.35rem;">
                            RM&nbsp;{{ number_format($stats['total_fines'], 2) }}
                        </div>
                    </div>
                    <div class="stat-card-icon" style="background:#fff1f2; color:#f43f5e;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Cards Row --}}
        <div class="row g-3">

            {{-- Recent Complaints --}}
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6 class="content-card-title">
                            <i class="bi bi-chat-square-text text-primary"></i>
                            Recent Complaints
                        </h6>
                        <a href="{{ route('complaints.create') }}" class="chip-new">
                            <i class="bi bi-plus-lg"></i> New
                        </a>
                    </div>
                    <div class="content-card-body">
                        @forelse($recent_complaints as $complaint)
                            <div class="row-item">
                                <div class="flex-grow-1 min-w-0">
                                    <a href="{{ route('complaints.show', $complaint) }}" class="row-item-title">
                                        {{ Str::limit($complaint->title, 50) }}
                                    </a>
                                    <span class="row-item-meta">
                                        {{ $complaint->created_at->format('d M Y, h:i a') }}
                                    </span>
                                </div>
                                <div class="flex-shrink-0">
                                    @php
                                        $cls = match ($complaint->status) {
                                            'completed' => 'status-completed',
                                            'processing' => 'status-processing',
                                            'rejected' => 'status-rejected',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $cls }}">{{ ucfirst($complaint->status) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-chat-dots"></i>
                                <p>No complaints history.<br>If you have any hostel issues, report them here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Action Required --}}
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6 class="content-card-title">
                            <i class="bi bi-exclamation-triangle text-danger"></i>
                            Action Required
                        </h6>
                        <a href="{{ route('compounds.index') }}" class="btn-outline-purple btn-sm"
                            style="padding:.3rem .8rem; font-size:.78rem;">
                            View All
                        </a>
                    </div>
                    <div class="content-card-body">
                        @forelse($unpaid_compounds as $compound)
                            <div class="row-item">
                                <div class="flex-grow-1 min-w-0">
                                    <span class="row-item-title d-block">{{ $compound->violation_type }}</span>
                                    <span class="row-item-meta">
                                        RM {{ number_format($compound->amount, 2) }}
                                        &bull; Due {{ $compound->due_date->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('payments.create', ['compound_id' => $compound->id]) }}"
                                        class="btn-purple" style="padding:.4rem .9rem; font-size:.8rem;">
                                        Pay Now
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-emoji-sunglasses" style="color:#22c55e; opacity:1;"></i>
                                <p><strong>All clear!</strong><br>You have no unpaid compounds.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>