<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Submit New Complaint</h2>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 720px;">
            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label form-label-custom">Category *</label>
                            <select name="category_id" id="category_id" class="form-select form-select-custom" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label form-label-custom">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="form-control form-control-custom" placeholder="Brief description of the issue"
                                required>
                            @error('title')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label form-label-custom">Description *</label>
                            <textarea name="description" id="description" rows="4"
                                class="form-control form-control-custom"
                                placeholder="Please provide detailed information about the problem..."
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label for="location" class="form-label form-label-custom">Location</label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', Auth::user()->room) }}"
                                class="form-control form-control-custom" placeholder="e.g., Block A, Room 101">
                            @error('location')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label for="priority" class="form-label form-label-custom">Priority *</label>
                            <select name="priority" id="priority" class="form-select form-select-custom" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low — Not urgent
                                </option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>
                                    Medium — Normal priority</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High — Urgent
                                    attention needed</option>
                            </select>
                            @error('priority')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label form-label-custom">Photo (Optional)</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="form-control form-control-custom">
                            <div class="text-muted text-xs mt-1">Upload a photo of the issue (max 10MB)</div>
                            @error('image')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4" style="border-color: #f1f5f9;">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('complaints.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-send me-1"></i>Submit Complaint
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>