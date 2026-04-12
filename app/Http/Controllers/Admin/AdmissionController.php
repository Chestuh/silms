<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    /**
     * Manage student admission records: application, admission, enrollment, transfer, leave, re-admission.
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $query = AdmissionRecord::with('student.user')->orderByDesc('created_at');
        if ($type && in_array($type, ['admission', 'transfer', 'readmission', 'leave'], true)) {
            $query->where('record_type', $type);
        }
        $records = $query->paginate(20);
        return view('admin.admission.index', compact('records', 'type'));
    }

    public function create(Request $request)
    {
        $students = Student::with('user')->orderBy('student_number')->get();
        return view('admin.admission.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'record_type' => ['required', 'in:admission,transfer,readmission,leave'],
            'date_processed' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        AdmissionRecord::create([
            'student_id' => $request->student_id,
            'record_type' => $request->record_type,
            'date_processed' => $request->date_processed ?: now(),
            'notes' => $request->notes,
        ]);
        return redirect()->route('admin.admission.index')->with('success', 'Admission record created.');
    }
}
