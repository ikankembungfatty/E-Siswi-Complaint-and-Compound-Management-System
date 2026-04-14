<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Page Title + Action --}}
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h4 class="fw-800 mb-0" style="color: var(--text-primary);">Complaints</h4>
                <p class="mb-0 mt-1" style="color: var(--text-muted); font-size:.85rem;">
                    Track and manage your hostel complaint reports
                </p>
            </div>
            @if(Auth::user()->isStudent())
                <a href="{{ route('complaints.create') }}" class="btn-purple">
                    <i class="bi bi-plus-lg"></i> New Complaint
                </a>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-3"
                style="background:#d1fae5; color:#065f46; border-left:4px solid #22c55e;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Complaints Table Card --}}
        <div class="content-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                    <thead>
                        <tr style="background: var(--bg-main);">
                            <th class="ps-4 py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Title</th>
                            @if(!Auth::user()->isStudent())
                                <th class="py-3 fw-700"
                                    style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                    Student</th>
                            @endif
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Category</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Priority</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Status</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Date</th>
                            <th class="pe-4 py-3 fw-700 text-end"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr style="border-bottom:1px solid var(--border); transition:background .15s ease;"
                                onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background=''">
                                <td class="ps-4 py-3">
                                    <div class="fw-600" style="color:var(--text-primary); font-size:.9rem;">
                                        {{ Str::limit($complaint->title, 40) }}
                                    </div>
                                    @if($complaint->location)
                                        <div style="font-size:.75rem; color:var(--text-muted); margin-top:.2rem;">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $complaint->location }}
                                        </div>
                                    @endif
                                </td>
                                @if(!Auth::user()->isStudent())
                                    <td class="py-3" style="font-size:.875rem; color:var(--text-secondary);">
                                        {{ $complaint->user->name }}
                                    </td>
                                @endif
                                <td class="py-3" style="font-size:.875rem; color:var(--text-secondary);">
                                    {{ $complaint->category->name }}
                                </td>
                                <td class="py-3">
                                    @php
                                        $pCls = match ($complaint->priority) {
                                            'high' => 'background:#fef3c7; color:#b45309;',
                                            'low' => 'background:#f0fdf4; color:#16a34a;',
                                            default => 'background:#eff6ff; color:#2563eb;',
                                        };
                                    @endphp
                                    <span class="status-badge" style="{{ $pCls }}">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $sCls = match ($complaint->status) {
                                            'completed' => 'status-completed',
                                            'processing' => 'status-processing',
                                            'in_progress' => 'status-processing',
                                            'rejected' => 'status-rejected',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $sCls }}">
                                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                    </span>
                                </td>
                                <td class="py-3" style="font-size:.8rem; color:var(--text-muted);">
                                    {{ $complaint->created_at->format('d M Y') }}
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('complaints.show', $complaint) }}" class="btn-outline-purple"
                                        style="padding:.35rem .9rem; font-size:.8rem;">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-inbox"></i>
                                        <p>No complaints found.<br>
                                            @if(Auth::user()->isStudent())
                                                <a href="{{ route('complaints.create') }}"
                                                    style="color:var(--purple); font-weight:600; text-decoration:none;">
                                                    Submit your first complaint →
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($complaints->hasPages())
                <div class="px-4 py-3 border-top" style="border-color:var(--border) !important;">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>