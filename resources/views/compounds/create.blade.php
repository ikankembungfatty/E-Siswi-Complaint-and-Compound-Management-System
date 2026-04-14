<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Issue Compound</h2>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 720px;">
            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <form method="POST" action="{{ route('compounds.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="form-label form-label-custom">Student *</label>
                            <select name="user_id" id="user_id" class="form-select form-select-custom" required>
                                <option value="">Select a student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->student_id }}) - {{ $student->room }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="violation_type" class="form-label form-label-custom">Violation Type *</label>
                            <input type="text" name="violation_type" id="violation_type"
                                value="{{ old('violation_type') }}" class="form-control form-control-custom"
                                placeholder="e.g., Late return, Noise violation" required>
                            @error('violation_type')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label form-label-custom">Description *</label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control form-control-custom" required>{{ old('description') }}</textarea>
                            @error('description')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="amount" class="form-label form-label-custom">Amount (RM) *</label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="1"
                                    step="0.01" class="form-control form-control-custom" required>
                                @error('amount')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="due_date" class="form-label form-label-custom">Due Date *</label>
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}"
                                    class="form-control form-control-custom" required>
                                @error('due_date')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: #f1f5f9;">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('compounds.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-danger-custom"><i
                                    class="bi bi-exclamation-triangle me-1"></i>Issue Compound</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>