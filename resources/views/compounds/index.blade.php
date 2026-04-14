<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h4 class="fw-800 mb-0" style="color: var(--text-primary);">Compounds</h4>
                <p class="mb-0 mt-1" style="color: var(--text-muted); font-size:.85rem;">
                    View and manage disciplinary compounds.
                </p>
            </div>
            @if(!Auth::user()->isStudent())
                <a href="{{ route('compounds.create') }}" class="btn"
                    style="background:#ef4444; color:white; font-weight:600; font-size:0.9rem; padding:0.4rem 1rem; border-radius:var(--radius-md);">
                    <i class="bi bi-plus me-1"></i> Issue Compound
                </a>
            @endif
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
                            @if(!Auth::user()->isStudent())
                                <th class="ps-4 py-3 fw-700"
                                    style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                    Student</th>
                            @endif
                            <th class="{{ Auth::user()->isStudent() ? 'ps-4' : '' }} py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Violation</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Amount</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Due Date</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Status</th>
                            <th class="pe-4 py-3 fw-700 text-end"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compounds as $compound)
                            <tr style="border-bottom:1px solid var(--border); transition:background .15s ease;"
                                onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background=''">
                                @if(!Auth::user()->isStudent())
                                    <td class="ps-4 py-3">
                                        <div class="fw-600" style="color:var(--text-primary); font-size:.9rem;">
                                            {{ $compound->user->name }}
                                        </div>
                                        <div
                                            style="font-size:.75rem; color:var(--text-muted); font-family:monospace; margin-top:.2rem;">
                                            ID: {{ $compound->user->student_id ?? 'N/A' }}
                                        </div>
                                    </td>
                                @endif
                                <td class="{{ Auth::user()->isStudent() ? 'ps-4' : '' }} py-3">
                                    <div class="fw-500" style="color:var(--text-primary); font-size:.875rem;">
                                        {{ $compound->violation_type }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold" style="color:#e11d48; font-size:.95rem;">
                                        RM {{ number_format($compound->amount, 2) }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div style="font-size:.85rem; color:var(--text-secondary);">
                                        <i
                                            class="bi bi-calendar-event me-1 text-muted"></i>{{ $compound->due_date->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    @php
                                        $sCls = match ($compound->status) {
                                            'paid' => 'status-completed',
                                            'unpaid' => 'status-rejected', // red outline
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $sCls }}">
                                        {{ ucfirst($compound->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a href="{{ route('compounds.show', $compound) }}" class="btn-outline-purple"
                                            style="padding:.25rem .6rem; font-size:.75rem;">
                                            View
                                        </a>
                                        @if(Auth::user()->isStudent() && !$compound->isPaid())
                                            <a href="{{ route('payments.create', ['compound_id' => $compound->id]) }}"
                                                class="btn"
                                                style="background:#16a34a; color:white; padding:.25rem .6rem; font-size:.75rem; font-weight:600; border-radius:var(--radius-sm);">
                                                Pay Now
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isStudent() ? '5' : '6' }}">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-shield-check d-block"
                                            style="font-size: 2.5rem; color:var(--text-muted); margin-bottom:.5rem;"></i>
                                        <p class="mb-0">No compounds found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($compounds->hasPages())
                <div class="px-4 py-3 border-top" style="border-color:var(--border) !important;">
                    {{ $compounds->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>