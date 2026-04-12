<?php

return [
    // Maximum allowed units per semester for academic load validation
    'max_units_per_semester' => env('EDU_MAX_UNITS_PER_SEMESTER', 24),

    // Minimum recommended units per semester
    'min_units_per_semester' => env('EDU_MIN_UNITS_PER_SEMESTER', 12),

    // Thresholds used for skill-gap detection (grade scale: lower is better)
    'skill_gap_grade_threshold' => env('EDU_SKILL_GAP_GRADE_THRESHOLD', 2.5),

    // Completion percent threshold for learning materials
    'skill_gap_completion_threshold' => env('EDU_SKILL_GAP_COMPLETION_THRESHOLD', 60),
];
