<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show(Fee $fee)
    {
        $student = request()->user()->student;
        if (!$student || $fee->student_id !== $student->id) {
            abort(403);
        }
        if ($fee->status === 'paid') {
            return redirect()->route('student.fees')->with('info', 'This fee is already paid.');
        }
        return view('student.payment.show', compact('fee'));
    }

    public function store(Request $request, Fee $fee)
    {
        $student = $request->user()->student;
        if (!$student || $fee->student_id !== $student->id) {
            abort(403);
        }
        if ($fee->status === 'paid') {
            return redirect()->route('student.fees')->with('info', 'This fee is already paid.');
        }

        $valid = $request->validate([
            'payment_method' => 'required|in:gcash,cash,bank_transfer',
            'payment_reference' => 'required|string|max:255',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $proofPath = 'payment-proofs/' . $fee->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($proofPath, file_get_contents($file));
            $proofPath = 'storage/' . $proofPath;
        }

        $fee->update([
            'payment_method' => $valid['payment_method'],
            'payment_reference' => $valid['payment_reference'],
            'payment_proof_path' => $proofPath,
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('student.fees')
            ->with('success', 'Payment submitted. Cashier will verify and mark as paid.');
    }
}
