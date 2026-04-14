<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Compound;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            return $this->studentDashboard();
        } elseif ($user->isWarden()) {
            return $this->wardenDashboard();
        } else {
            return $this->hepaDashboard();
        }
    }

    private function studentDashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_complaints' => Complaint::where('user_id', $user->id)->count(),
            'processing_complaints' => Complaint::where('user_id', $user->id)->where('status', 'processing')->count(),
            'completed_complaints' => Complaint::where('user_id', $user->id)->where('status', 'completed')->count(),
            'unpaid_compounds' => Compound::where('user_id', $user->id)->where('status', 'unpaid')->count(),
            'total_fines' => Compound::where('user_id', $user->id)->where('status', 'unpaid')->sum('amount'),
        ];

        $recent_complaints = Complaint::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unpaid_compounds = Compound::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.student', compact('stats', 'recent_complaints', 'unpaid_compounds'));
    }

    private function wardenDashboard()
    {
        $user = Auth::user();

        $stats = [
            'processing_complaints' => Complaint::where('status', 'processing')->count(),
            'my_assigned' => Complaint::where('assigned_to', $user->id)->whereIn('status', ['processing', 'in_progress'])->count(),
            'completed_today' => Complaint::where('assigned_to', $user->id)->whereDate('completed_at', today())->count(),
            'compounds_issued' => Compound::where('issued_by', $user->id)->count(),
            'total_students' => User::students()->count(),
        ];

        $pending_complaints = Complaint::where('status', 'processing')
            ->with(['user', 'category'])
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        $my_complaints = Complaint::where('assigned_to', $user->id)
            ->whereIn('status', ['processing', 'in_progress'])
            ->with(['user', 'category'])
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.warden', compact('stats', 'pending_complaints', 'my_complaints'));
    }

    private function hepaDashboard()
    {
        $stats = [
            'total_complaints' => Complaint::count(),
            'processing_complaints' => Complaint::where('status', 'processing')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'completed_complaints' => Complaint::where('status', 'completed')->count(),
            'total_compounds' => Compound::count(),
            'unpaid_compounds' => Compound::where('status', 'unpaid')->count(),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'total_students' => User::students()->count(),
            'total_wardens' => User::wardens()->count(),
        ];

        $recent_complaints = Complaint::with(['user', 'category', 'assignedWarden'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recent_payments = Payment::with(['user', 'compound'])
            ->where('status', 'completed')
            ->orderBy('paid_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.hepa', compact('stats', 'recent_complaints', 'recent_payments'));
    }
}
