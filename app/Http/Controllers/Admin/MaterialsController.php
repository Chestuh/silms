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
        $search = $request->query('search', '');

        $materialsQuery = LearningMaterial::with('course.instructor.user');

        // Apply search filter
        if ($search) {
            $materialsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('course', function ($courseQuery) use ($search) {
                      $courseQuery->where('code', 'like', "%{$search}%")
                          ->orWhere('title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course.instructor.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($activeTab === 'archived') {
            $materials = $materialsQuery->where('archived', true)
                ->orderByDesc('updated_at')
                ->paginate(15)->withQueryString();
        } else {
            $materials = $materialsQuery->where('archived', false)
                ->where(function($q) {
                    $q->whereNull('approval_status')
                      ->orWhere('approval_status', '!=', 'rejected');
                })
                ->orderByDesc('created_at')
                ->paginate(15)->withQueryString();
        }

        // also load recently rejected materials for quick access
        $rejectedMaterials = LearningMaterial::with('course.instructor.user')
            ->where('approval_status', 'rejected')
            ->orderByDesc('updated_at')
            ->paginate(10, ['*'], 'rejected_page');

        return view('admin.materials.index', compact('materials', 'rejectedMaterials', 'activeTab', 'search'));
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
