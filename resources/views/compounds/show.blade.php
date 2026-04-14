<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-header-title">Compound Details</h2>
            <a href="{{ route('compounds.index') }}" class="action-link"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 720px;">
            @if(session('success'))
                <div class="alert alert-custom-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-1" style="color: #1e293b;">{{ $compound->violation_type }}</h3>
                            <small class="text-muted">Issued on
                                {{ $compound->created_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <span class="badge-status badge-{{ $compound->status }}">{{ ucfirst($compound->status) }}</span>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="form-label-custom">Student</div>
                            <div class="fw-500" style="color: #1e293b;">{{ $compound->user->name }}</div>
                            <small class="text-muted">{{ $compound->user->student_id }} -
                                {{ $compound->user->room }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Issued By</div>
                            <div class="fw-500" style="color: #1e293b;">{{ $compound->issuedBy->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Amount</div>
                            <div class="fw-bold fs-4" style="color: #e11d48;">RM
                                {{ number_format($compound->amount, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Due Date</div>
                            <div class="{{ $compound->isOverdue() ? 'fw-bold text-danger' : '' }}"
                                style="color: #1e293b;">{{ $compound->due_date->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-label-custom">Description</div>
                        <div class="desc-box mt-2">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $compound->description }}</p>
                        </div>
                    </div>

                    @if(Auth::user()->isStudent() && !$compound->isPaid())
                        <hr class="my-4">
                        <a href="{{ route('payments.create', ['compound_id' => $compound->id]) }}"
                            class="btn btn-success-custom">
                            <i class="bi bi-credit-card me-1"></i>Pay Now — RM {{ number_format($compound->amount, 2) }}
                        </a>
                    @endif

                    @if($compound->payments->count() > 0)
                        <hr class="my-4">
                        <h5 class="fw-semibold mb-3" style="color: #1e293b;">Payment History</h5>
                        @foreach($compound->payments as $payment)
                            <div class="info-panel info-panel-green mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-500 small" style="color: #1e293b;">{{ $payment->transaction_id }}</div>
                                        <div class="text-muted text-xs">{{ $payment->paid_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">RM {{ number_format($payment->amount, 2) }}</div>
                                        <a href="{{ route('payments.show', $payment) }}" class="action-link text-xs">View
                                            Receipt</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>