<?php

namespace App\Http\Controllers;

use App\Models\Compound;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $payments = Payment::where('user_id', $user->id)
                ->with('compound')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Wardens and HEPA Staff see all payments
            $payments = Payment::with(['user', 'compound'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $compound_id = $request->query('compound_id');
        $compound = Compound::findOrFail($compound_id);

        // Only the student who received the compound can pay
        if (Auth::user()->isStudent() && $compound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($compound->isPaid()) {
            return redirect()->route('compounds.show', $compound)
                ->with('error', 'This compound has already been paid.');
        }

        return view('payments.create', compact('compound'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'compound_id' => 'required|exists:compounds,id',
            'receipt_image' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $compound = Compound::findOrFail($validated['compound_id']);

        // Only the student who received the compound can pay
        if (Auth::user()->isStudent() && $compound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($compound->isPaid()) {
            return redirect()->route('compounds.show', $compound)
                ->with('error', 'This compound has already been paid.');
        }

        // Store receipt image
        $receiptPath = $request->file('receipt_image')->store('receipts', 'public');

        // Create payment record
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'compound_id' => $compound->id,
            'amount' => $compound->amount,
            'payment_method' => 'jompay',
            'status' => 'completed',
            'paid_at' => now(),
            'receipt_image' => $receiptPath,
        ]);

        // Update compound status to paid
        $compound->update(['status' => 'paid']);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Your compound has been successfully paid. Thank you!');
    }

    /**
     * Display the specified resource (Receipt).
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'compound.issuedBy']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        // Payments cannot be edited once completed
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Payments cannot be updated once completed
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Payments cannot be deleted
        abort(403);
    }

    /**
     * Print receipt
     */
    public function receipt(Payment $payment)
    {
        $payment->load(['user', 'compound.issuedBy']);
        return view('payments.receipt', compact('payment'));
    }
}
