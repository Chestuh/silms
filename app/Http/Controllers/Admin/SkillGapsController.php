<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class SkillGapsController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();
        $reports = [];
        foreach ($students as $s) {
            $gaps = $s->skillGaps();
            if (!empty($gaps)) {
                $reports[] = ['student' => $s, 'gaps' => $gaps];
            }
        }
        return view('admin.reports.skill-gaps', compact('reports'));
    }
}
