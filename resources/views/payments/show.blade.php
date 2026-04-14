<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-header-title">Payment Receipt</h2>
            <button onclick="window.print()" class="btn btn-primary-custom btn-sm d-print-none">
                <i class="bi bi-printer me-1"></i>Print Receipt
            </button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 640px;">
            @if(session('success'))
                <!-- Success Modal (auto-show) -->
                <div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow-lg"
                            style="overflow: hidden; max-width: 400px; margin: 0 auto;">
                            <div class="modal-body text-center p-5">
                                <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                    style="width: 80px; height: 80px; background-color: #ecfdf5; border: 3px solid #10b981;">
                                    <i class="bi bi-check-lg"
                                        style="font-size: 44px; color: #10b981; -webkit-text-stroke: 1px;"></i>
                                </div>
                                <h4 class="fw-bold mb-3" style="color: #007755;">Payment Successful!</h4>
                                <p class="text-muted mb-0" style="font-size: 15px;">Your compound has been successfully
                                    paid. Thank you!</p>
                            </div>
                            <div class="modal-footer border-0 justify-content-center pb-5 pt-0">
                                <button type="button" class="btn text-white px-4 py-2"
                                    style="background-color: #00a877; border-radius: 20px; font-weight: 500; min-width: 100px;"
                                    data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        new bootstrap.Modal(document.getElementById('successModal')).show();
                    });
                </script>
            @endif

            <div class="card-custom" id="receipt">
                <div class="p-4 p-sm-5">
                    <!-- Header -->
                    <div class="text-center border-bottom pb-4 mb-4">
                        <h3 class="fw-bold" style="color: #1e293b;">E-SISWI</h3>
                        <p class="text-muted small mb-0">Complaint & Compound Management System</p>
                        <p class="text-muted small mb-0">UPTM Female Hostel</p>
                    </div>

                    <!-- Receipt Title -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-success">PAYMENT RECEIPT</h4>
                        <p class="font-mono" style="color: #1e293b;">{{ $payment->transaction_id }}</p>
                    </div>

                    <!-- Details -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Date & Time:</span>
                            <span class="fw-500"
                                style="color: #1e293b;">{{ $payment->paid_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Student Name:</span>
                            <span class="fw-500" style="color: #1e293b;">{{ $payment->user->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Student ID:</span>
                            <span class="fw-500" style="color: #1e293b;">{{ $payment->user->student_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Violation:</span>
                            <span class="fw-500" style="color: #1e293b;">{{ $payment->compound->violation_type }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Payment Method:</span>
                            <span class="fw-500"
                                style="color: #1e293b;">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Status:</span>
                            <span class="fw-500 text-success">{{ ucfirst($payment->status) }}</span>
                        </div>
                        @if($payment->receipt_image)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Uploaded Receipt:</span>
                                <span>
                                    <a href="{{ asset('storage/' . $payment->receipt_image) }}" target="_blank"
                                        class="fw-500 text-decoration-none" style="color: #4f46e5;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>View File
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Amount -->
                    <div class="info-panel info-panel-green text-center p-4">
                        <div class="text-muted small mb-1">Amount Paid</div>
                        <div class="fw-bold fs-3 text-success">RM {{ number_format($payment->amount, 2) }}</div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="text-muted small mb-1">This is a computer-generated receipt. No signature required.
                        </p>
                        <p class="text-muted small">Thank you for your payment!</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 d-print-none">
                <a href="{{ route('dashboard') }}" class="action-link"><i class="bi bi-arrow-left me-1"></i>Back to
                    Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>