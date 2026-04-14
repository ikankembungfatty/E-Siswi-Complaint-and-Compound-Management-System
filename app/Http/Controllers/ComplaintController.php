<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $complaints = Complaint::where('user_id', $user->id)
                ->with(['category', 'assignedWarden'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->isWarden() || $user->isHepaStaff()) {
            $complaints = Complaint::with(['user', 'category'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            abort(403);
        }

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ComplaintCategory::active()->get();
        return view('complaints.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:complaint_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'image' => 'nullable|image|max:10240',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'processing';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('complaints', 'public');
        }

        Complaint::create($validated);

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint submitted successfully! We will address it soon.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'assignedWarden']);

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $categories = ComplaintCategory::active()->get();
        return view('complaints.edit', compact('complaint', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $user = Auth::user();

        // Students can only update their own pending complaints
        if ($user->isStudent()) {
            if ($complaint->user_id !== $user->id || !$complaint->isProcessing()) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'nullable|string|max:255',
                'priority' => 'required|in:low,medium,high',
            ]);

            $complaint->update($validated);
        } else {
            // Warden or HEPA can update status
            $validated = $request->validate([
                'status' => 'required|in:processing,in_progress,completed',
                'resolution_notes' => 'nullable|string',
            ]);

            if ($validated['status'] === 'completed') {
                $validated['completed_at'] = now();
            }

            if ($user->isWarden() && !$complaint->assigned_to) {
                $validated['assigned_to'] = $user->id;
            }

            $complaint->update($validated);
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        if (Auth::user()->isStudent() && $complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($complaint->image) {
            Storage::disk('public')->delete($complaint->image);
        }

        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint deleted successfully!');
    }


    /**
     * Download complaint report as PDF.
     */
    public function downloadPdf(Complaint $complaint)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $complaint->load(['user', 'category', 'assignedWarden']);

        $pdf = Pdf::loadView('complaints.pdf', compact('complaint'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'complaint-report-CMP-' . str_pad($complaint->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
