<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private const DASHBOARD_LAYOUTS = [
        'balanced' => 'Balanced layout',
        'compact' => 'Compact layout',
        'spacious' => 'Spacious layout',
    ];

    private const LEARNING_FOCUS_OPTIONS = [
        'academic_progress' => 'Academic progress',
        'learning_materials' => 'Learning materials',
        'assessment_readiness' => 'Assessment readiness',
        'self_assessment' => 'Self-assessment',
    ];

    public function index(Request $request)
    {
        $maxUnits = (int) Setting::getValue('max_units_per_semester', config('app.max_units_per_semester', 24));
        $allowPersonalization = Setting::getValue('allow_student_personalization', '1') === '1';
        $dashboardLayout = Setting::getValue('default_dashboard_layout', 'balanced');
        $learningFocus = Setting::getValue('default_learning_focus', 'academic_progress');
        $appName = config('app.name');
        $tagline = config('app.tagline', '');

        return view('admin.settings.index', compact(
            'maxUnits',
            'allowPersonalization',
            'dashboardLayout',
            'learningFocus',
            'appName',
            'tagline'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_units_per_semester' => 'required|integer|min:1|max:99',
            'allow_student_personalization' => 'nullable|boolean',
            'default_dashboard_layout' => 'required|in:' . implode(',', array_keys(self::DASHBOARD_LAYOUTS)),
            'default_learning_focus' => 'required|in:' . implode(',', array_keys(self::LEARNING_FOCUS_OPTIONS)),
        ]);

        Setting::setValue('max_units_per_semester', $request->max_units_per_semester);
        Setting::setValue('allow_student_personalization', $request->boolean('allow_student_personalization') ? '1' : '0');
        Setting::setValue('default_dashboard_layout', $request->default_dashboard_layout);
        Setting::setValue('default_learning_focus', $request->default_learning_focus);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Personalization defaults saved.');
    }
}
