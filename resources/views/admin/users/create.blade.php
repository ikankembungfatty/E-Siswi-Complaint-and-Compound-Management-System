<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header-title">Add {{ ucfirst($type) }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 720px;">
            <div class="card-custom">
                <div class="p-4 p-sm-5">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label form-label-custom">Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="form-control form-control-custom" required>
                            @error('name')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label form-label-custom">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control form-control-custom" required>
                            @error('email')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label form-label-custom">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="form-control form-control-custom">
                            @error('phone')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        @if($type === 'student')
                            <!-- Student ID -->
                            <div class="mb-4">
                                <label for="student_id" class="form-label form-label-custom">Student ID *</label>
                                <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}"
                                    class="form-control form-control-custom" required>
                                @error('student_id')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="block" class="form-label form-label-custom">Block *</label>
                                    <select name="block" id="block" class="form-select form-select-custom" required>
                                        <option value="">Select</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $b)
                                            <option value="{{ $b }}" {{ old('block') == $b ? 'selected' : '' }}>Block {{ $b }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('block')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="room_level" class="form-label form-label-custom">Level *</label>
                                    <select name="room_level" id="room_level" class="form-select form-select-custom"
                                        required>
                                        <option value="">Select</option>
                                        @foreach(['1', '2', '3', '4', '5'] as $l)
                                            <option value="{{ $l }}" {{ old('room_level') == $l ? 'selected' : '' }}>Level
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_level')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="room" class="form-label form-label-custom">Room *</label>
                                    <input type="text" name="room" id="room" value="{{ old('room') }}"
                                        placeholder="e.g., A-101" class="form-control form-control-custom" required>
                                    @error('room')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @endif

                        <!-- Password -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label form-label-custom">Password *</label>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-custom" required>
                                @error('password')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label form-label-custom">Confirm Password
                                    *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control form-control-custom" required>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: #f1f5f9;">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ $type === 'warden' ? route('admin.wardens') : route('admin.students') }}"
                                class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom">Create {{ ucfirst($type) }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>