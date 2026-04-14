<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Make Payment</h2>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 640px;">
            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <!-- Compound Summary -->
                    <div class="desc-box mb-4 text-center">
                        <div class="form-label-custom mb-1">Payment For</div>
                        <h5 class="fw-bold mb-1" style="color: #1e293b;">{{ $compound->violation_type }}</h5>
                        <small class="text-muted">Due: {{ $compound->due_date->format('d M Y') }}</small>
                        <div class="fw-bold fs-3 mt-2" style="color: #e11d48;">RM
                            {{ number_format($compound->amount, 2) }}
                        </div>
                    </div>

                    <!-- JomPay Instructions -->
                    <div class="mb-4">
                        <div class="form-label-custom mb-3">
                            <i class="bi bi-info-circle me-1"></i>How to Pay via JomPay
                        </div>
                        <div class="p-3 rounded-3" style="background: #f0f7ff; border: 1px solid #bfdbfe;">
                            <div class="mb-3">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge rounded-circle bg-primary me-2"
                                        style="width: 24px; height: 24px; line-height: 24px; font-size: 12px;">1</span>
                                    <div>
                                        <div class="fw-500 small" style="color: #1e293b;">Open your online banking app
                                            and select <strong>JomPay</strong></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge rounded-circle bg-primary me-2"
                                        style="width: 24px; height: 24px; line-height: 24px; font-size: 12px;">2</span>
                                    <div>
                                        <div class="fw-500 small" style="color: #1e293b;">Enter the following details:
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-4 ps-2">
                                    <table class="table table-sm table-borderless mb-0" style="font-size: 13px;">
                                        <tr>
                                            <td class="text-muted fw-500 py-1" style="width: 130px;">Biller Code</td>
                                            <td class="py-1">
                                                <span class="fw-bold"
                                                    style="color: #1e40af; font-size: 15px;">88070</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500 py-1">Reference 1</td>
                                            <td class="py-1">
                                                <span class="fw-bold"
                                                    style="color: #dc2626;">{{ Auth::user()->student_id ?? 'Your Student ID' }}</span>
                                                <span class="text-muted ms-1" style="font-size: 11px;">(No. Kad
                                                    Pelajar)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500 py-1">Reference 2</td>
                                            <td class="py-1">
                                                <span class="fw-bold" style="color: #dc2626;">Kompaun Asrama</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500 py-1">Amount</td>
                                            <td class="py-1">
                                                <span class="fw-bold" style="color: #dc2626;">RM
                                                    {{ number_format($compound->amount, 2) }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge rounded-circle bg-primary me-2"
                                        style="width: 24px; height: 24px; line-height: 24px; font-size: 12px;">3</span>
                                    <div>
                                        <div class="fw-500 small" style="color: #1e293b;">Confirm the payment and
                                            <strong>save/screenshot</strong> the receipt
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-items-start">
                                    <span class="badge rounded-circle bg-primary me-2"
                                        style="width: 24px; height: 24px; line-height: 24px; font-size: 12px;">4</span>
                                    <div>
                                        <div class="fw-500 small" style="color: #1e293b;">Upload the receipt below to
                                            complete the process</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 px-1">
                            <small class="text-danger fw-500"><i class="bi bi-exclamation-triangle me-1"></i>Sila
                                pastikan anda mengisi ruang <strong>REFERENCE</strong> sebagaimana yang telah
                                <strong>DITETAPKAN</strong></small>
                        </div>
                    </div>

                    <!-- Receipt Upload Form -->
                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data"
                        id="paymentForm">
                        @csrf
                        <input type="hidden" name="compound_id" value="{{ $compound->id }}">

                        <div class="mb-4">
                            <label for="receipt_image" class="form-label form-label-custom">Upload Receipt *</label>
                            <div class="upload-area p-4 text-center rounded-3" id="uploadArea"
                                style="border: 2px dashed #cbd5e1; cursor: pointer; transition: all 0.2s;">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up fs-1" style="color: #94a3b8;"></i>
                                    <div class="small text-muted mt-2">Click or drag your receipt image here</div>
                                    <div class="text-muted" style="font-size: 11px;">JPG, PNG, PDF — Max 5MB</div>
                                </div>
                                <div id="uploadPreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Receipt preview" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                    <div class="small text-success mt-2 fw-500"><i
                                            class="bi bi-check-circle me-1"></i>Receipt uploaded</div>
                                </div>
                                <input type="file" name="receipt_image" id="receipt_image"
                                    accept="image/jpeg,image/png,image/jpg,application/pdf" class="d-none" required>
                            </div>
                            @error('receipt_image')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4" style="border-color: #f1f5f9;">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('compounds.show', $compound) }}"
                                class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-success-custom" id="uploadBtn" disabled>
                                <i class="bi bi-upload me-1"></i>Upload Receipt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upload-area:hover {
            border-color: #818cf8 !important;
            background: #fafafe;
        }

        .upload-area.dragover {
            border-color: #4f46e5 !important;
            background: #eef2ff;
        }

        .upload-area.has-file {
            border-color: #22c55e !important;
            border-style: solid !important;
            background: #f0fdf4;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('receipt_image');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            const uploadPreview = document.getElementById('uploadPreview');
            const previewImg = document.getElementById('previewImg');
            const uploadBtn = document.getElementById('uploadBtn');

            // Click to upload
            uploadArea.addEventListener('click', () => fileInput.click());

            // Drag & Drop
            uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
            uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    showPreview(e.dataTransfer.files[0]);
                }
            });

            // File selected
            fileInput.addEventListener('change', function () {
                if (this.files.length) showPreview(this.files[0]);
            });

            function showPreview(file) {
                if (!file.type.match('image.*') && file.type !== 'application/pdf') return;
                if (file.type === 'application/pdf') {
                    previewImg.src = '';
                    previewImg.style.display = 'none';
                    uploadPlaceholder.style.display = 'none';
                    uploadPreview.style.display = 'block';
                    uploadPreview.querySelector('.small').innerHTML = '<i class="bi bi-file-earmark-pdf me-1"></i>' + file.name;
                    uploadArea.classList.add('has-file');
                    uploadBtn.disabled = false;
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewImg.style.display = '';
                    uploadPlaceholder.style.display = 'none';
                    uploadPreview.style.display = 'block';
                    uploadArea.classList.add('has-file');
                    uploadBtn.disabled = false;
                };
                reader.readAsDataURL(file);
            }

            // Show loading state on submit
            document.getElementById('paymentForm').addEventListener('submit', function () {
                uploadBtn.disabled = true;
                uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Uploading...';
            });
        });
    </script>
</x-app-layout>