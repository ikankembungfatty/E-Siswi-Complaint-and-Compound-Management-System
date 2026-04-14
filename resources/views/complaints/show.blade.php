<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Breadcrumb/Header --}}
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            <a href="{{ route('complaints.index') }}" class="icon-btn" aria-label="Back"
                style="border:1px solid var(--border); border-radius:var(--radius-sm);">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-800 mb-0" style="color:var(--text-primary);">Complaint Details</h4>
                <p class="mb-0" style="color:var(--text-muted); font-size:.82rem;">
                    Submitted {{ $complaint->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="ms-auto d-flex gap-2">
                @php
                    $sCls = match ($complaint->status) {
                        'completed' => 'status-completed',
                        'processing' => 'status-processing',
                        'in_progress' => 'status-processing',
                        'rejected' => 'status-rejected',
                        default => 'status-pending',
                    };
                    $pCls = match ($complaint->priority) {
                        'high' => 'background:#fef3c7; color:#b45309;',
                        'low' => 'background:#f0fdf4; color:#16a34a;',
                        default => 'background:#eff6ff; color:#2563eb;',
                    };
                @endphp
                <span class="status-badge {{ $sCls }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span>
                <span class="status-badge" style="{{ $pCls }}">{{ ucfirst($complaint->priority) }}</span>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-3"
                style="background:#d1fae5; color:#065f46; border-left:4px solid #22c55e;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-3"
                style="background:#fee2e2; color:#991b1b; border-left:4px solid #ef4444;">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row g-4">
            {{-- Main Detail Card --}}
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="content-card-title">
                            <i class="bi bi-file-text text-primary"></i>
                            {{ $complaint->title }}
                        </h5>
                    </div>
                    <div class="content-card-body" style="padding: 1.5rem;">

                        {{-- Meta Grid --}}
                        <div class="row g-3 mb-4">
                            @if(Auth::user()->isAdmin())
                                <div class="col-sm-6">
                                    <div
                                        style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.35rem;">
                                        Submitted By</div>
                                    <div class="fw-600" style="color:var(--text-primary);">{{ $complaint->user->name }}
                                    </div>
                                    <div style="font-size:.8rem; color:var(--text-muted);">
                                        {{ $complaint->user->student_id ?? 'No ID' }}</div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div
                                    style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.35rem;">
                                    Category</div>
                                <div class="fw-600" style="color:var(--text-primary);">{{ $complaint->category->name }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div
                                    style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.35rem;">
                                    Location</div>
                                <div class="fw-600" style="color:var(--text-primary);">
                                    {{ $complaint->location ?? 'Not specified' }}</div>
                            </div>
                            @if($complaint->assignedWarden)
                                <div class="col-sm-6">
                                    <div
                                        style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.35rem;">
                                        Assigned To</div>
                                    <div class="fw-600" style="color:var(--text-primary);">
                                        {{ $complaint->assignedWarden->name }}</div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div
                                    style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.35rem;">
                                    Date Submitted</div>
                                <div class="fw-600" style="color:var(--text-primary);">
                                    {{ $complaint->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <div
                                style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.5rem;">
                                Description</div>
                            <div class="p-3 rounded-3"
                                style="background:var(--bg-main); color:var(--text-secondary); line-height:1.75; white-space:pre-wrap; font-size:.9rem;">
                                {{ $complaint->description }}</div>
                        </div>

                        {{-- Attached Image --}}
                        @if($complaint->image)
                            <div>
                                <div
                                    style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.5rem;">
                                    Attached Photo</div>
                                <img src="{{ Storage::url($complaint->image) }}" alt="Complaint photo"
                                    class="rounded-3 border"
                                    style="max-width:100%; max-height:360px; object-fit:cover; border-color:var(--border) !important; cursor:pointer;"
                                    onclick="window.open(this.src,'_blank')">
                                <div style="font-size:.75rem; color:var(--text-muted); margin-top:.35rem;">
                                    <i class="bi bi-zoom-in me-1"></i>Click image to view full size
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar: Status / Actions --}}
            <div class="col-lg-4">

                {{-- Resolution Notes --}}
                @if($complaint->resolution_notes)
                    <div class="content-card mb-3">
                        <div class="content-card-header">
                            <h6 class="content-card-title">
                                <i class="bi bi-check-circle text-success"></i> Resolution Notes
                            </h6>
                        </div>
                        <div class="content-card-body" style="padding:1.25rem;">
                            <p
                                style="color:var(--text-secondary); font-size:.875rem; line-height:1.7; margin-bottom:.5rem;">
                                {{ $complaint->resolution_notes }}
                            </p>
                            @if($complaint->completed_at)
                                <small style="color:var(--text-muted);">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Completed on {{ $complaint->completed_at->format('d M Y, h:i A') }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Admin Update Status --}}
                @if(Auth::user()->isAdmin() && !$complaint->isCompleted())
                    <div class="content-card">
                        <div class="content-card-header">
                            <h6 class="content-card-title">
                                <i class="bi bi-pencil-square text-warning"></i> Update Status
                            </h6>
                        </div>
                        <div class="content-card-body" style="padding:1.25rem;">
                            <form method="POST" action="{{ route('complaints.update', $complaint) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="status"
                                        style="font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); display:block; margin-bottom:.35rem;">Status</label>
                                    <select name="status" id="status" class="form-select"
                                        style="border-radius:var(--radius-sm); border:1.5px solid var(--border); background:var(--bg-card); color:var(--text-primary); font-size:.875rem; padding:.65rem .9rem;">
                                        <option value="processing" {{ $complaint->status == 'processing' ? 'selected' : '' }}>
                                            Processing</option>
                                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $complaint->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="resolution_notes"
                                        style="font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); display:block; margin-bottom:.35rem;">Resolution
                                        Notes</label>
                                    <textarea name="resolution_notes" id="resolution_notes" rows="4" class="form-control"
                                        style="border-radius:var(--radius-sm); border:1.5px solid var(--border); background:var(--bg-card); color:var(--text-primary); font-size:.875rem; resize:vertical;"
                                        placeholder="Add notes about the resolution...">{{ $complaint->resolution_notes }}</textarea>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <button type="submit" class="btn-purple w-100 justify-content-center">
                                        <i class="bi bi-check2"></i> Update Complaint
                                    </button>
                                    <a href="{{ route('complaints.pdf', $complaint) }}"
                                        class="btn-outline-purple w-100 justify-content-center"
                                        style="text-decoration:none; display:inline-flex;">
                                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->isAdmin() && $complaint->isCompleted())
                    <div class="content-card">
                        <div class="content-card-body" style="padding:1.25rem;">
                            <a href="{{ route('complaints.pdf', $complaint) }}"
                                class="btn-outline-purple w-100 justify-content-center"
                                style="text-decoration:none; display:inline-flex;">
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>