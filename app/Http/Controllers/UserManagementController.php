<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function students()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $students = User::students()->orderBy('name')->paginate(15);
        return view('admin.users.students', compact('students'));
    }

    /**
     * Display a listing of wardens (HEPA only).
     */
    public function wardens()
    {
        if (!Auth::user()->isHepaStaff()) {
            abort(403);
        }

        $wardens = User::wardens()->orderBy('name')->paginate(15);
        return view('admin.users.wardens', compact('wardens'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'student');

        // Only HEPA can create wardens
        if ($type === 'warden' && !Auth::user()->isHepaStaff()) {
            abort(403);
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('admin.users.create', compact('type'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'student');

        // Only HEPA can create wardens
        if ($type === 'warden' && !Auth::user()->isHepaStaff()) {
            abort(403);
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($type === 'student') {
            $rules['student_id'] = ['required', 'string', 'max:50', 'unique:users'];
            $rules['block'] = ['required', 'string', 'max:10'];
            $rules['room'] = ['required', 'string', 'max:20'];
            $rules['room_level'] = ['required', 'string', 'max:10'];
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $type,
            'student_id' => $validated['student_id'] ?? null,
            'block' => $validated['block'] ?? null,
            'room' => $validated['room'] ?? null,
            'room_level' => $validated['room_level'] ?? null,
        ]);

        $route = $type === 'warden' ? 'admin.wardens' : 'admin.students';
        return redirect()->route($route)
            ->with('success', ucfirst($type) . ' created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Only HEPA can view warden profiles
        if ($user->isWarden() && !Auth::user()->isHepaStaff()) {
            abort(403);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Only HEPA can edit wardens
        if ($user->isWarden() && !Auth::user()->isHepaStaff()) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Only HEPA can update wardens
        if ($user->isWarden() && !Auth::user()->isHepaStaff()) {
            abort(403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($user->isStudent()) {
            $rules['student_id'] = ['required', 'string', 'max:50', 'unique:users,student_id,' . $user->id];
            $rules['block'] = ['required', 'string', 'max:10'];
            $rules['room'] = ['required', 'string', 'max:20'];
            $rules['room_level'] = ['required', 'string', 'max:10'];
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $route = $user->isWarden() ? 'admin.wardens' : 'admin.students';
        return redirect()->route($route)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Don't allow deleting HEPA staff
        if ($user->isHepaStaff()) {
            return back()->with('error', 'Cannot delete HEPA staff users.');
        }

        // Only HEPA can delete wardens
        if ($user->isWarden() && !Auth::user()->isHepaStaff()) {
            return back()->with('error', 'Only HEPA staff can delete warden accounts.');
        }

        $route = $user->isWarden() ? 'admin.wardens' : 'admin.students';
        $user->delete();

        return redirect()->route($route)
            ->with('success', 'User deleted successfully!');
    }
}
