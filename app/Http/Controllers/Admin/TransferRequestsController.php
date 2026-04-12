<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferRequestsController extends Controller
{
    public function index()
    {
        $requests = TransferRequest::with('student.user')->orderBy('requested_at', 'desc')->get();
        return view('admin.transfer-requests.index', compact('requests'));
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
