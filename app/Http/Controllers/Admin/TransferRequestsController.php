<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRecord;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferRequestsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = TransferRequest::with('student.user')->orderBy('requested_at', 'desc');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $requests = $query->get();
        $counts = [
            'all' => TransferRequest::count(),
            'pending' => TransferRequest::where('status', 'pending')->count(),
            'approved' => TransferRequest::where('status', 'approved')->count(),
            'rejected' => TransferRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.transfer-requests.index', compact('requests', 'status', 'counts'));
    }

    public function show(TransferRequest $transferRequest)
    {
        $transferRequest->load('student.user');
        return view('admin.transfer-requests.show', compact('transferRequest'));
    }

    public function approve(TransferRequest $transferRequest)
    {
        $transferRequest->status = 'approved';
        $transferRequest->processed_at = now();
        $transferRequest->save();

        AdmissionRecord::create([
            'student_id' => $transferRequest->student_id,
            'record_type' => 'transfer',
            'date_processed' => now(),
            'notes' => 'Approved transfer request from '.($transferRequest->from_school ?: 'N/A').' to '.($transferRequest->to_school ?: 'N/A').' '.($transferRequest->notes ? '('.$transferRequest->notes.')' : ''),
        ]);

        return redirect()->route('admin.transfer-requests.index')->with('success', 'Transfer request approved.');
    }

    public function reject(TransferRequest $transferRequest)
    {
        $transferRequest->status = 'rejected';
        $transferRequest->processed_at = now();
        $transferRequest->save();
        return redirect()->route('admin.transfer-requests.index')->with('success', 'Transfer request rejected.');
    }
}
