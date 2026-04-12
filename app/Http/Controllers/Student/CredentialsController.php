<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CredentialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CredentialsController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $requests = $student->credentialRequests()->orderByDesc('created_at')->get();
        return view('student.credentials', compact('requests'));
    }

    public function store(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $data = $request->validate([
            'credential_type' => ['required', 'string', 'max:100'],
        ]);

        CredentialRequest::create([
            'student_id' => $student->id,
            'credential_type' => $data['credential_type'],
        ]);

        return redirect()->route('student.credentials.index')->with('success', 'Request submitted.');
    }

    public function downloadLetter(CredentialRequest $credential_request)
    {
        $student = request()->user()->student;
        if (!$student || $credential_request->student_id !== $student->id) {
            abort(403);
        }
        if (!$credential_request->letter_path) {
            abort(404, 'Letter not yet available.');
        }
        $path = storage_path('app/' . $credential_request->letter_path);
        if (!file_exists($path)) {
            abort(404, 'Letter file not found.');
        }
        $filename = 'credential-letter-' . preg_replace('/[^a-zA-Z0-9\-_]/', '-', $credential_request->credential_type) . '.pdf';
        return response()->download($path, $filename, ['Content-Type' => 'application/pdf']);
    }
}
