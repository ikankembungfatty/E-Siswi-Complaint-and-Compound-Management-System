<x-app-layout>
    <div class="container-fluid px-0">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
            <div>
                <h4 class="fw-800 mb-0" style="color: var(--text-primary);">Manage Students</h4>
                <p class="mb-0 mt-1" style="color: var(--text-muted); font-size:.85rem;">
                    View and manage all registered student accounts.
                </p>
            </div>
            <a href="{{ route('admin.users.create', ['type' => 'student']) }}" class="btn-purple">
                <i class="bi bi-person-plus-fill me-1"></i> Add Student
            </a>
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
                                Student Details</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Contact</th>
                            <th class="py-3 fw-700"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Location</th>
                            <th class="pe-4 py-3 fw-700 text-end"
                                style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1px solid var(--border);">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr style="border-bottom:1px solid var(--border); transition:background .15s ease;"
                                onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background=''">
                                <td class="ps-4 py-3">
                                    <div class="fw-600" style="color:var(--text-primary); font-size:.9rem;">
                                        {{ $student->name }}
                                    </div>
                                    <div
                                        style="font-size:.75rem; color:var(--text-muted); font-family:monospace; margin-top:.2rem;">
                                        ID: {{ $student->student_id ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div style="font-size:.875rem; color:var(--text-secondary);">{{ $student->email }}</div>
                                    @if($student->phone)
                                        <div style="font-size:.75rem; color:var(--text-muted); margin-top:.2rem;">
                                            <i class="bi bi-telephone-fill me-1"></i>{{ $student->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div style="font-size:.875rem; color:var(--text-secondary);">
                                        <span class="status-badge" style="background:#f1f5f9; color:#475569;">
                                            Block {{ $student->block }}
                                        </span>
                                    </div>
                                    <div style="font-size:.75rem; color:var(--text-muted); margin-top:.35rem;">
                                        Room {{ $student->room }}
                                    </div>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.users.edit', $student) }}" class="btn-outline-purple"
                                            style="padding:.25rem .6rem; font-size:.75rem;">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger"
                                            style="padding:.25rem .6rem; font-size:.75rem; border-radius:var(--radius-sm); display:inline-flex; align-items:center;"
                                            data-bs-toggle="modal" data-bs-target="#deleteStudentModal-{{ $student->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteStudentModal-{{ $student->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                                                    <div class="modal-header border-0 pb-0 mt-2 px-4">
                                                        <h5 class="modal-title fw-semibold text-danger">Confirm Deletion
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body px-4 text-start">
                                                        <p class="text-muted mb-0" style="white-space: normal;">
                                                            Are you sure you want to permanently delete the student
                                                            <strong>{{ $student->name }}</strong>? This action cannot be
                                                            undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-3 px-4 pb-4">
                                                        <button type="button"
                                                            class="btn text-muted fw-500 rounded-pill px-4"
                                                            style="background:#f8fafc; border:1px solid #e2e8f0;"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.users.destroy', $student) }}"
                                                            method="POST" class="m-0">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn"
                                                                style="background:#ef4444; color:white; font-weight:600; border-radius:8px; padding:0.4rem 1.2rem;">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-people d-block"
                                            style="font-size: 2.5rem; color:var(--text-muted); margin-bottom:.5rem;"></i>
                                        <p class="mb-0">No students registered yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="px-4 py-3 border-top" style="border-color:var(--border) !important;">
                    {{ $students->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>