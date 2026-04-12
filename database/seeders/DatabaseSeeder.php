<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\LearningMaterial;
use Database\Seeders\GradeCourseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@csp.edu'],
            [
                'name' => 'Portal Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $cashier = User::firstOrCreate(
            ['email' => 'cashier@csp.edu'],
            [
                'name' => 'Portal Cashier',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ]
        );

        $instructorUser = User::firstOrCreate(
            ['email' => 'instructor@csp.edu'],
            [
                'name' => 'Jane Instructor',
                'password' => Hash::make('password'),
                'role' => 'instructor',
            ]
        );
        $instructor = Instructor::firstOrCreate(
            ['user_id' => $instructorUser->id],
            ['department' => 'Computer Science']
        );

        $this->call([
            GradeCourseSeeder::class,
        ]);

        $studentUser = User::firstOrCreate(
            ['email' => 'student@csp.edu'],
            [
                'name' => 'John Student',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'student_number' => '2024-001',
                'program' => 'BS Computer Science',
                'year_level' => 1,
                'admission_date' => now(),
                'status' => 'active',
            ]
        );

        $course1 = Course::firstOrCreate(
            ['code' => 'CS101'],
            [
                'title' => 'Introduction to Programming',
                'units' => 3.00,
                'instructor_id' => $instructor->id,
            ]
        );
        $course2 = Course::firstOrCreate(
            ['code' => 'CS102'],
            [
                'title' => 'Data Structures',
                'units' => 3.00,
                'instructor_id' => $instructor->id,
            ]
        );

        foreach ([
            ['course_id' => $course1->id, 'title' => 'Welcome and Syllabus', 'description' => 'Course overview and requirements', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 1],
            ['course_id' => $course1->id, 'title' => 'Variables and Data Types', 'description' => 'Video lesson on basics', 'format' => 'video', 'difficulty_level' => 'easy', 'order_index' => 2],
            ['course_id' => $course2->id, 'title' => 'Arrays and Lists', 'description' => 'Introduction to linear structures', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 1],
        ] as $attrs) {
            LearningMaterial::firstOrCreate(
                ['course_id' => $attrs['course_id'], 'title' => $attrs['title']],
                $attrs
            );
        }

        $e1 = Enrollment::firstOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course1->id,
                'semester' => '1st',
                'school_year' => '2024-2025',
            ],
            ['status' => 'enrolled']
        );
        $e2 = Enrollment::firstOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course2->id,
                'semester' => '1st',
                'school_year' => '2024-2025',
            ],
            ['status' => 'enrolled']
        );

        Grade::updateOrCreate(
            ['enrollment_id' => $e1->id],
            ['midterm_grade' => 88, 'final_grade' => 90]
        );
        Grade::updateOrCreate(
            ['enrollment_id' => $e2->id],
            ['midterm_grade' => 85, 'final_grade' => 87]
        );
    }
}
