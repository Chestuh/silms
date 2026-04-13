<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferRequestsController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }

        $requests = $student->transferRequests()->orderByDesc('requested_at')->get();

        return view('student.transfer-requests.index', compact('requests'));
    }

    public function create(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }

        return view('student.transfer-requests.create');
    }

    public function store(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }

        $validated = $request->validate([
            'from_school' => ['required', 'string', 'max:255'],
            'to_school' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['student_id'] = $student->id;
        $validated['status'] = 'pending';
        $validated['requested_at'] = now();

        TransferRequest::create($validated);

        return redirect()->route('student.transfer-requests.index')->with('success', 'Transfer request submitted successfully.');
    }
}
