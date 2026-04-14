<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h4 class="fw-800 mb-0" style="color: var(--text-primary);">Payment History</h4>
                <p class="mb-0 mt-1" style="color: var(--text-muted); font-size:.85rem;">
                    View logs for compound payments.
                </p>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-3"
                style="background:#d1fae5; color:#065f46; border-left:4px solid #22c55e;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Data Table --}}
        <div class="content-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                    <thead>
                        <tr style="background: var(--bg-main);">
                            <th class="ps-4 py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Transaction ID</th>
                            @if(!Auth::user()->isStudent())
                                <th class="py-3 fw-700"
                                    style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                    Student</th>
                            @endif
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Violation</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Amount</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Date</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Status</th>
                            <th class="pe-4 py-3 fw-700 text-end"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr style="border-bottom:1px solid var(--border); transition:background .15s ease;"
                                onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background=''">
                                <td class="ps-4 py-3">
                                    <div
                                        style="font-size:.75rem; color:var(--text-muted); font-family:monospace; padding:0.2rem 0.4rem; background:white; border:1px solid var(--border); border-radius:4px; display:inline-block;">
                                        {{ $payment->transaction_id }}
                                    </div>
                                </td>
                                @if(!Auth::user()->isStudent())
                                    <td class="py-3">
                                        <div class="fw-500" style="color:var(--text-primary); font-size:.875rem;">
                                            {{ $payment->user->name }}
                                        </div>
                                    </td>
                                @endif
                                <td class="py-3">
                                    <div style="font-size:.85rem; color:var(--text-secondary);">
                                        {{ $payment->compound->violation_type }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold" style="color:#16a34a; font-size:.95rem;">
                                        RM {{ number_format($payment->amount, 2) }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div style="font-size:.85rem; color:var(--text-secondary);">
                                        <i class="bi bi-clock me-1 text-muted"></i>{{ $payment->paid_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    @php
                                        $sCls = match ($payment->status) {
                                            'successful' => 'status-completed',
                                            'failed' => 'status-rejected',
                                            'pending' => 'status-pending',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $sCls }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('payments.show', $payment) }}"
                                        class="btn-outline-purple d-inline-flex align-items-center gap-1"
                                        style="padding:.25rem .6rem; font-size:.75rem;">
                                        <i class="bi bi-receipt"></i> Receipt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isStudent() ? '6' : '7' }}">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-receipt d-block"
                                            style="font-size: 2.5rem; color:var(--text-muted); margin-bottom:.5rem;"></i>
                                        <p class="mb-0">No payment history found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="px-4 py-3 border-top" style="border-color:var(--border) !important;">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>