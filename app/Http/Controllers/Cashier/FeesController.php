<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class FeesController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with('student.user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        $fees = $query->orderBy('due_date')->orderByDesc('created_at')->paginate(20);
        return view('cashier.fees.index', compact('fees'));
    }

    public function create()
    {
        $enrolledCount = Student::where('status', 'active')->count();
        return view('cashier.fees.create', compact('enrolledCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_type' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $students = Student::where('status', 'active')->get();
        $count = 0;
        foreach ($students as $student) {
            Fee::create([
                'student_id' => $student->id,
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
                'due_date' => $request->due_date ?: null,
                'status' => 'pending',
            ]);
            $count++;
        }

        return redirect()
            ->route('cashier.fees.index')
            ->with('success', "Fee added to {$count} enrolled student(s).");
    }

    public function show(Fee $fee)
    {
        $fee->load('student.user');
        return view('cashier.fees.show', compact('fee'));
    }

    public function markPaid(Fee $fee)
    {
        $fee->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_status' => 'verified',
        ]);
        return redirect()
            ->route('cashier.fees.index')
            ->with('success', 'Fee marked as paid.');
    }

    public function verifyPayment(Request $request, Fee $fee)
    {
        $request->validate([
            'action' => 'required|in:verify,reject',
        ]);
        if ($request->action === 'verify') {
            $fee->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_status' => 'verified',
            ]);
            $message = 'Payment verified and fee marked as paid.';
        } else {
            $fee->update(['payment_status' => 'rejected']);
            $message = 'Payment rejected. Student can resubmit with correct information.';
        }
        return redirect()
            ->route('cashier.fees.show', $fee)
            ->with('success', $message);
    }
}
