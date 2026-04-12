<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaryRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
{
    /**
     * Student disciplinary records: violations, sanctions, conduct monitoring.
     */
    public function index(Request $request)
    {
        $records = DisciplinaryRecord::with('student.user')
            ->orderByDesc('incident_date')
            ->paginate(20);
        return view('admin.disciplinary.index', compact('records'));
    }

    public function create(Request $request)
    {
        $students = Student::with('user')->orderBy('student_number')->get();
        return view('admin.disciplinary.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'incident_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
            'sanction' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'in:pending,resolved,appealed'],
        ]);
        DisciplinaryRecord::create([
            'student_id' => $request->student_id,
            'incident_date' => $request->incident_date,
            'description' => $request->description,
            'sanction' => $request->sanction,
            'status' => $request->status ?? 'pending',
        ]);
        return redirect()->route('admin.disciplinary.index')->with('success', 'Disciplinary record added.');
    }

    public function edit(DisciplinaryRecord $disciplinary)
    {
        $disciplinary->load('student.user');
        return view('admin.disciplinary.edit', compact('disciplinary'));
    }

    public function update(Request $request, DisciplinaryRecord $disciplinary)
    {
        $request->validate([
            'incident_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
            'sanction' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:pending,resolved,appealed'],
        ]);
        $disciplinary->update([
            'incident_date' => $request->incident_date,
            'description' => $request->description,
            'sanction' => $request->sanction,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.disciplinary.index')->with('success', 'Disciplinary record updated.');
    }

    public function destroy(DisciplinaryRecord $disciplinary)
    {
        $disciplinary->delete();
        return redirect()->route('admin.disciplinary.index')->with('success', 'Disciplinary record deleted.');
    }
}
