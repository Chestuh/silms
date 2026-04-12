<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\MaterialRating;
use Illuminate\Http\Request;

class RateMaterialController extends Controller
{
    public function edit(Request $request, LearningMaterial $material)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);
        if ($material->archived) abort(404);

        $rating = MaterialRating::where('student_id', $student->id)->where('material_id', $material->id)->first();
        return view('student.rate-material', compact('material', 'rating'));
    }

    public function update(Request $request, LearningMaterial $material)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        MaterialRating::updateOrCreate(
            ['student_id' => $student->id, 'material_id' => $material->id],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? '']
        );

        return redirect()->route('student.learning.index')->with('success', 'Rating saved.');
    }
}
