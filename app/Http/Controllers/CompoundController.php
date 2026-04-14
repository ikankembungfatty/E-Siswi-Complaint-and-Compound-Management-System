<?php

namespace App\Http\Controllers;

use App\Models\Compound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $compounds = Compound::where('user_id', $user->id)
                ->with('issuedBy')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->isWarden()) {
            $compounds = Compound::where('issued_by', $user->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // HEPA Staff sees all
            $compounds = Compound::with(['user', 'issuedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('compounds.index', compact('compounds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only wardens and HEPA staff can create compounds
        if (Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $students = User::where('role', 'student')->orderBy('name')->get();
        return view('compounds.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'violation_type' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date|after:today',
        ]);

        $validated['issued_by'] = Auth::id();
        $validated['status'] = 'unpaid';

        Compound::create($validated);

        return redirect()->route('compounds.index')
            ->with('success', 'Compound issued successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compound $compound)
    {
        $compound->load(['user', 'issuedBy', 'payments']);
        return view('compounds.show', compact('compound'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compound $compound)
    {
        if (Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $students = User::where('role', 'student')->orderBy('name')->get();
        return view('compounds.edit', compact('compound', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compound $compound)
    {
        if (Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'violation_type' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,paid,overdue',
        ]);

        $compound->update($validated);

        return redirect()->route('compounds.show', $compound)
            ->with('success', 'Compound updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compound $compound)
    {
        if (Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $compound->delete();

        return redirect()->route('compounds.index')
            ->with('success', 'Compound deleted successfully!');
    }
}
