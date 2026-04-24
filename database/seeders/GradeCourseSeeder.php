<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class GradeCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates courses for different grade levels.
     * You can extend this to add courses for other departments.
     */
    public function run(): void
    {
        // Get a default instructor (if installed)
        $defaultInstructor = Instructor::first();
        $instructorId = $defaultInstructor?->id;

        // ============================================================
        // GRADE 7 COURSES
        // ============================================================
        $grade7Courses = [
            ['code' => 'G7 - ENG', 'title' => 'English 7', 'units' => 3],
            ['code' => 'G7 - FIL', 'title' => 'Filipino 7', 'units' => 2],
            ['code' => 'G7 - MATH', 'title' => 'Mathematics 7', 'units' => 3],
            ['code' => 'G7 - SCI', 'title' => 'Science 7', 'units' => 3],
            ['code' => 'G7 - AP', 'title' => 'Araling Panlipunan 7', 'units' => 3],
            ['code' => 'G7 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 7', 'units' => 2],
            ['code' => 'G7 - MAPEH', 'title' => 'MAPEH 7 (Music, Arts, PE, Health)', 'units' => 2],
            ['code' => 'G7 - TLE', 'title' => 'Technology and Livelihood Education 7', 'units' => 2],
        ];

        foreach ($grade7Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '7',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        // ============================================================
        // GRADE 8 COURSES
        // ============================================================
        $grade8Courses = [
            ['code' => 'G8 - ENG', 'title' => 'English 8', 'units' => 3],
            ['code' => 'G8 - FIL', 'title' => 'Filipino 8', 'units' => 2],
            ['code' => 'G8 - MATH', 'title' => 'Mathematics 8 (Algebra)', 'units' => 3],
            ['code' => 'G8 - SCI', 'title' => 'Science 8 (Biology)', 'units' => 3],
            ['code' => 'G8 - AP', 'title' => 'Araling Panlipunan 8 (Asian Studies)', 'units' => 3],
            ['code' => 'G8 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 8', 'units' => 2],
            ['code' => 'G8 - MAPEH', 'title' => 'MAPEH 8 (Music, Arts, PE, Health)', 'units' => 2],
            ['code' => 'G8 - TLE', 'title' => 'Technology and Livelihood Education 8', 'units' => 2],
        ];

        foreach ($grade8Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '8',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        // ============================================================
        // GRADE 9 COURSES
        // ============================================================
        $grade9Courses = [
            ['code' => 'G9 - ENG', 'title' => 'English 9', 'units' => 3],
            ['code' => 'G9 - FIL', 'title' => 'Filipino 9', 'units' => 2],
            ['code' => 'G9 - MATH', 'title' => 'Mathematics 9 (Geometry)', 'units' => 3],
            ['code' => 'G9 - SCI', 'title' => 'Science 9 (Chemistry)', 'units' => 3],
            ['code' => 'G9 - AP', 'title' => 'Araling Panlipunan 9 (Economics)', 'units' => 3],
            ['code' => 'G9 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 9', 'units' => 2],
            ['code' => 'G9 - MAPEH', 'title' => 'MAPEH 9 (Music, Arts, PE, Health)', 'units' => 2],
            ['code' => 'G9 - TLE', 'title' => 'Technology and Livelihood Education 9', 'units' => 2],
        ];

        foreach ($grade9Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '9',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        // ============================================================
        // GRADE 10 COURSES
        // ============================================================
        $grade10Courses = [
            ['code' => 'G10 - ENG', 'title' => 'English 10', 'units' => 3],
            ['code' => 'G10 - FIL', 'title' => 'Filipino 10', 'units' => 2],
            ['code' => 'G10 - MATH', 'title' => 'Mathematics 10 (Statistics & Probability / Advanced Algebra)', 'units' => 3],
            ['code' => 'G10 - SCI', 'title' => 'Science 10 (Physics)', 'units' => 3],
            ['code' => 'G10 - AP', 'title' => 'Araling Panlipunan 10 (Contemporary Issues)', 'units' => 3],
            ['code' => 'G10 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 10', 'units' => 2],
            ['code' => 'G10 - MAPEH', 'title' => 'MAPEH 10 (Music, Arts, PE, Health)', 'units' => 2],
            ['code' => 'G10 - TLE', 'title' => 'Technology and Livelihood Education 10', 'units' => 2],
        ];

        foreach ($grade10Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '10',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        // ============================================================
        // GRADE 11 COURSES - 1st Semester
        // ============================================================
        $grade11Courses = [
            ['code' => 'G11 - OC', 'title' => 'Oral Communication in Context', 'units' => 3],
            ['code' => 'G11 - RW', 'title' => 'Reading and Writing', 'units' => 3],
            ['code' => 'G11 - 21CL', 'title' => '21st Century Literature from the Philippines and the World', 'units' => 3],
            ['code' => 'G11 - GM', 'title' => 'General Mathematics', 'units' => 3],
            ['code' => 'G11 - SP', 'title' => 'Statistics and Probability', 'units' => 3],
            ['code' => 'G11 - ELS', 'title' => 'Earth and Life Science', 'units' => 3],
            ['code' => 'G11 - PEH', 'title' => 'Physical Education and Health 11', 'units' => 2],
            ['code' => 'G11 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 11', 'units' => 3],
        ];

        foreach ($grade11Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '11',
                    'semester' => '1st Semester',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        // ============================================================
        // GRADE 12 COURSES - 1st Semester
        // ============================================================
        $grade12Courses = [
            ['code' => 'G12 - TW', 'title' => 'Technical Writing and Business Writing', 'units' => 3],
            ['code' => 'G12 - FIL', 'title' => 'Filipino 12', 'units' => 3],
            ['code' => 'G12 - ENG', 'title' => 'English 12', 'units' => 3],
            ['code' => 'G12 - CALC', 'title' => 'Basic Calculus', 'units' => 3],
            ['code' => 'G12 - BIO', 'title' => 'Biology', 'units' => 3],
            ['code' => 'G12 - PHY', 'title' => 'Physics', 'units' => 3],
            ['code' => 'G12 - PEH', 'title' => 'Physical Education and Health 12', 'units' => 2],
            ['code' => 'G12 - ESP', 'title' => 'Edukasyon sa Pagpapakatao 12', 'units' => 3],
        ];

        foreach ($grade12Courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'units' => $course['units'],
                    'grade_level' => '12',
                    'semester' => '1st Semester',
                    'instructor_id' => $instructorId,
                ]
            );
        }

        echo "Grade courses seeded successfully!\n";
    }
}
