<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">My Tasks</h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-custom-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="card-custom">
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Complaint</th>
                                    <th>Student</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Scheduled</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td class="fw-500" style="color: #1e293b;">
                                            {{ Str::limit($task->complaint->title, 30) }}</td>
                                        <td class="text-muted">{{ $task->complaint->user->name }}</td>
                                        <td class="text-muted">{{ $task->complaint->location ?? '-' }}</td>
                                        <td class="text-muted">{{ $task->complaint->category->name }}</td>
                                        <td class="text-muted text-xs">
                                            {{ $task->scheduled_date ? $task->scheduled_date->format('d M Y') : '-' }}</td>
                                        <td>
                                            <span
                                                class="badge-status badge-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('technician.tasks.show', $task) }}"
                                                class="action-link">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="bi bi-clipboard-check fs-1 d-block mb-2"></i>No tasks assigned
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $tasks->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>