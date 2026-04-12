<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'all');

        if ($activeTab === 'archived') {
            $materials = LearningMaterial::with('course.instructor.user')
                ->where('archived', true)
                ->orderByDesc('updated_at')
                ->paginate(15);
        } else {
            $materials = LearningMaterial::with('course.instructor.user')
                ->where('archived', false)
                ->where(function($q) {
                    $q->whereNull('approval_status')
                      ->orWhere('approval_status', '!=', 'rejected');
                })
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        // also load recently rejected materials for quick access
        $rejectedMaterials = LearningMaterial::with('course.instructor.user')
            ->where('approval_status', 'rejected')
            ->orderByDesc('updated_at')
            ->paginate(10, ['*'], 'rejected_page');

        return view('admin.materials.index', compact('materials', 'rejectedMaterials', 'activeTab'));
    }

    public function approve(LearningMaterial $material)
    {
        $material->update(['approval_status' => 'approved', 'admin_comment' => null]);
        return back()->with('success', 'Material approved.');
    }

    public function reject(Request $request, LearningMaterial $material)
    {
        $request->validate(['admin_comment' => ['required', 'string', 'max:1000']]);
        $material->update(['approval_status' => 'rejected', 'admin_comment' => $request->admin_comment]);
        return back()->with('success', 'Material rejected.');
    }

    public function archive(LearningMaterial $material)
    {
        $material->update(['archived' => true]);
        return back()->with('success', 'Material archived.');
    }

    public function unarchive(LearningMaterial $material)
    {
        $material->update(['archived' => false]);
        return back()->with('success', 'Material restored from archive.');
    }

    public function destroy(LearningMaterial $material)
    {
        $material->delete();
        return back()->with('success', 'Material permanently deleted.');
    }
}
