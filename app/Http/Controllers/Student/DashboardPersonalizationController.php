<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserDashboardPreference;
use Illuminate\Http\Request;

class DashboardPersonalizationController extends Controller
{
    private const KPI_WIDGETS = [
        'enrolled_courses' => 'Enrolled Courses',
        'materials_completed' => 'Materials Completed',
        'materials_started' => 'Materials Started',
        'average_grade' => 'Average Grade',
        'gwa' => 'GWA',
        'pending_fees' => 'Pending Fees',
        'study_time_minutes' => 'Study Time (min)',
        'credentials_pending' => 'Credentials Pending',
        'unread_messages' => 'Unread Messages',
    ];

    /**
     * Customization and personalization: dashboard layout, preferences, learning focus.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $pref = $user->dashboardPreference;

        return view('student.dashboard-personalization', [
            'pref' => $pref,
            'widgetOptions' => self::KPI_WIDGETS,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'layout' => ['nullable', 'in:default,compact,focus'],
            'learning_focus' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'show_quick_links' => ['nullable', 'boolean'],
            'show_my_info' => ['nullable', 'boolean'],
            'widgets' => ['nullable', 'array'],
            'widgets.*.enabled' => ['nullable', 'boolean'],
            'widgets.*.order' => ['nullable', 'integer', 'min:1', 'max:'.count(self::KPI_WIDGETS)],
        ]);

        $user = $request->user();
        $pref = $user->dashboardPreference ?? new UserDashboardPreference(['user_id' => $user->id]);
        $pref->layout = $request->layout ?? 'default';
        $pref->learning_focus = $request->learning_focus;
        $pref->theme_color = $request->theme_color ?: '#0d6efd';
        $pref->show_quick_links = $request->boolean('show_quick_links', true);
        $pref->show_my_info = $request->boolean('show_my_info', true);

        $widgetPreferences = [];
        foreach (self::KPI_WIDGETS as $settingKey => $label) {
            $enabled = $request->boolean("widgets.$settingKey.enabled", true);
            $order = $request->input("widgets.$settingKey.order");
            $widgetPreferences[$settingKey] = [
                'enabled' => $enabled,
                'order' => $order !== null ? (int) $order : array_search($settingKey, array_keys(self::KPI_WIDGETS), true) + 1,
            ];
        }

        $pref->widgets = $widgetPreferences;
        $pref->user_id = $user->id;
        $pref->save();

        return redirect()->route('student.dashboard-personalization')->with('success', 'Dashboard preferences saved.');
    }
}
