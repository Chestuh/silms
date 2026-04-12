<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courses = $instructor->courses()->withCount(['learningMaterials', 'enrollments'])->orderBy('code')->get();
        return view('instructor.courses.index', compact('courses'));
    }
}
