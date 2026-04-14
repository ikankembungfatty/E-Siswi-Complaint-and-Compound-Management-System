<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-header-title">Task Details</h2>
            <a href="{{ route('technician.tasks') }}" class="action-link"><i class="bi bi-arrow-left me-1"></i>Back to
                Tasks</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 900px;">
            @if(session('success'))
                <div class="alert alert-custom-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-1" style="color: #1e293b;">{{ $assignment->complaint->title }}</h3>
                            <small class="text-muted">Assigned on
                                {{ $assignment->created_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <span
                            class="badge-status badge-{{ $assignment->status }}">{{ ucfirst(str_replace('_', ' ', $assignment->status)) }}</span>
                    </div>

                    <!-- Task Details -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="form-label-custom">Student</div>
                            <div class="fw-500" style="color: #1e293b;">{{ $assignment->complaint->user->name }}</div>
                            <small class="text-muted">{{ $assignment->complaint->user->phone ?? 'No phone' }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Location</div>
                            <div class="fw-semibold" style="color: #1e293b;">
                                {{ $assignment->complaint->location ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Category</div>
                            <div style="color: #1e293b;">{{ $assignment->complaint->category->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-custom">Scheduled Date</div>
                            <div style="color: #1e293b;">
                                {{ $assignment->scheduled_date ? $assignment->scheduled_date->format('d M Y') : 'Not scheduled yet' }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <div class="form-label-custom">Problem Description</div>
                        <div class="desc-box mt-2">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $assignment->complaint->description }}</p>
                        </div>
                    </div>

                    <!-- Complaint Image -->
                    @if($assignment->complaint->image)
                        <div class="mb-4">
                            <div class="form-label-custom">Photo of Issue</div>
                            <img src="{{ Storage::url($assignment->complaint->image) }}" alt="Issue"
                                class="mt-2 rounded-xl border" style="max-width: 400px;">
                        </div>
                    @endif

                    <!-- Completion Evidence -->
                    @if($assignment->isCompleted() && $assignment->completion_image)
                        <div class="info-panel info-panel-green mb-4">
                            <h6 class="fw-semibold mb-2" style="color: #065f46;">✅ Completion Report</h6>
                            <p style="color: #475569;">{{ $assignment->technician_notes }}</p>
                            <img src="{{ Storage::url($assignment->completion_image) }}" alt="Completion"
                                class="mt-2 rounded-xl border" style="max-width: 400px;">
                            <small class="text-muted d-block mt-2">Completed:
                                {{ $assignment->completed_at->format('d M Y, h:i A') }}</small>
                        </div>
                    @endif

                    <!-- Accept Task -->
                    @if($assignment->isPending())
                        <hr class="my-4">
                        <h5 class="fw-semibold mb-3" style="color: #1e293b;">Accept Task</h5>
                        <form method="POST" action="{{ route('technician.tasks.accept', $assignment) }}">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="scheduled_date" class="form-label form-label-custom">Select Date to Fix
                                        *</label>
                                    <input type="date" name="scheduled_date" id="scheduled_date"
                                        class="form-control form-control-custom" min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="technician_notes" class="form-label form-label-custom">Notes (Optional)</label>
                                <textarea name="technician_notes" id="technician_notes" rows="2"
                                    class="form-control form-control-custom"
                                    placeholder="Any notes about the scheduled visit..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success-custom"><i class="bi bi-check2 me-1"></i>Accept &
                                Schedule</button>
                        </form>

                        <form method="POST" action="{{ route('technician.tasks.reject', $assignment) }}" class="mt-4">
                            @csrf
                            <details>
                                <summary class="text-danger small fw-500" style="cursor: pointer;">Reject this task
                                </summary>
                                <div class="info-panel mt-3" style="background: #fef2f2; border-color: #fecaca;">
                                    <div class="mb-3">
                                        <label for="reject_notes" class="form-label form-label-custom">Reason for rejection
                                            *</label>
                                        <textarea name="technician_notes" rows="2" required
                                            class="form-control form-control-custom"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger-custom btn-sm">Reject Task</button>
                                </div>
                            </details>
                        </form>
                    @endif

                    <!-- Start Working -->
                    @if($assignment->isAccepted())
                        <hr class="my-4">
                        <form method="POST" action="{{ route('technician.tasks.start', $assignment) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary-custom"><i class="bi bi-wrench me-1"></i>Start
                                Working</button>
                        </form>
                    @endif

                    <!-- Complete Task -->
                    @if($assignment->isInProgress())
                        <hr class="my-4">
                        <h5 class="fw-semibold mb-3" style="color: #1e293b;">Complete Task</h5>
                        <form method="POST" action="{{ route('technician.tasks.complete', $assignment) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="technician_notes" class="form-label form-label-custom">Completion Report
                                    *</label>
                                <textarea name="technician_notes" id="technician_notes" rows="4" required
                                    class="form-control form-control-custom"
                                    placeholder="Describe what was done to fix the issue...">{{ old('technician_notes') }}</textarea>
                                @error('technician_notes')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="completion_image" class="form-label form-label-custom">Photo Evidence *</label>
                                <input type="file" name="completion_image" id="completion_image" accept="image/*" required
                                    class="form-control form-control-custom">
                                <div class="text-muted text-xs mt-1">Upload a photo showing the completed work (max 5MB)
                                </div>
                                @error('completion_image')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-success-custom"><i class="bi bi-check2-all me-1"></i>Mark
                                as Completed</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>