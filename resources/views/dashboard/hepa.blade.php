<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Admin Greeting Banner --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="esiswi-banner position-relative overflow-hidden">
                    <div class="position-relative z-1">
                        <h3 class="fw-800 text-white mb-2" style="font-size: 1.75rem;">HEPA Admin Dashboard</h3>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">System overview, complaints, and
                            compound management.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid (8 cards) --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid var(--purple-main);">
                    <div class="stat-label">Total Complaints</div>
                    <div class="stat-value">{{ $stats['total_complaints'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #f59e0b;">
                    <div class="stat-label">Processing</div>
                    <div class="stat-value">{{ $stats['processing_complaints'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #3b82f6;">
                    <div class="stat-label">In Progress</div>
                    <div class="stat-value" style="color: #2563eb;">{{ $stats['in_progress'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #10b981;">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $stats['completed_complaints'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #8b5cf6;">
                    <div class="stat-label">Total Compounds</div>
                    <div class="stat-value" style="color: #7c3aed;">{{ $stats['total_compounds'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #ef4444;">
                    <div class="stat-label">Unpaid Fines</div>
                    <div class="stat-value">{{ $stats['unpaid_compounds'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #10b981;">
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value" style="color: #059669; font-size: 1.25rem;">RM
                        {{ number_format($stats['total_payments'], 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl">
                <div class="stat-card" style="border-top: 4px solid #6366f1;">
                    <div class="stat-label">Wardens</div>
                    <div class="stat-value" style="color: #4f46e5;">{{ $stats['total_wardens'] }}</div>
                </div>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="row g-4">
            {{-- Recent Complaints --}}
            <div class="col-lg-8">
                <div class="content-card h-100">
                    <div class="content-card-header d-flex justify-content-between align-items-center">
                        <h5 class="content-card-title mb-0">Recent Complaints</h5>
                        <a href="{{ route('complaints.index') }}" class="btn-outline-purple"
                            style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">View All</a>
                    </div>
                    <div class="content-card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0"
                                style="border-collapse: separate; border-spacing: 0;">
                                <thead>
                                    <tr style="background: var(--bg-main);">
                                        <th class="ps-4 py-3 fw-700"
                                            style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border);">
                                            Title</th>
                                        <th class="py-3 fw-700"
                                            style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border);">
                                            Student</th>
                                        <th class="py-3 fw-700"
                                            style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border);">
                                            Category</th>
                                        <th class="pe-4 py-3 fw-700 text-end"
                                            style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border);">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_complaints as $complaint)
                                        <tr style="border-bottom: 1px solid var(--border); transition: background 0.15s;"
                                            onmouseover="this.style.background='var(--bg-main)'"
                                            onmouseout="this.style.background=''">
                                            <td class="ps-4 py-3">
                                                <a href="{{ route('complaints.show', $complaint) }}"
                                                    class="fw-600 text-decoration-none"
                                                    style="color: var(--text-primary); font-size: 0.9rem;">
                                                    {{ Str::limit($complaint->title, 35) }}
                                                </a>
                                            </td>
                                            <td class="py-3" style="font-size: 0.875rem; color: var(--text-secondary);">
                                                {{ $complaint->user->name }}</td>
                                            <td class="py-3" style="font-size: 0.875rem; color: var(--text-secondary);">
                                                {{ $complaint->category->name }}</td>
                                            <td class="pe-4 py-3 text-end">
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
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="empty-state py-4">
                                                    <p class="mb-0">No recent complaints.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Payments --}}
            <div class="col-lg-4">
                <div class="content-card h-100">
                    <div class="content-card-header d-flex justify-content-between align-items-center">
                        <h5 class="content-card-title mb-0">Recent Payments</h5>
                        <a href="{{ route('payments.index') }}" class="btn-outline-purple"
                            style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">View All</a>
                    </div>
                    <div class="content-card-body p-3">
                        @forelse($recent_payments as $payment)
                            <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded border"
                                style="background: var(--bg-main);">
                                <div>
                                    <div class="fw-600" style="color: var(--text-primary); font-size: 0.85rem;">
                                        {{ $payment->user->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">
                                        {{ $payment->transaction_id }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color: #16a34a; font-size: 0.9rem;">RM
                                        {{ number_format($payment->amount, 2) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ $payment->paid_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state py-5">
                                <p class="mb-0">No recent payments.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>