<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\LearningMaterial;
use App\Models\LearningProgress;
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
            ['email' => 'cheezyflrs@csp.edu'],
            [
                'name' => 'Chester John Flores',
                'password' => Hash::make('password'),
                'role' => 'instructor',
            ]
        );
        $instructor = Instructor::firstOrCreate(
            ['user_id' => $instructorUser->id],
            ['department' => 'Grade 7']
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

        // Create multiple courses for better presentation dashboard
        $courses = [];
        $courseData = [
            ['code' => 'CS101', 'title' => 'Introduction to Programming', 'units' => 3.00],
            ['code' => 'CS102', 'title' => 'Data Structures', 'units' => 3.00],
            ['code' => 'CS201', 'title' => 'Web Development Fundamentals', 'units' => 3.00],
            ['code' => 'CS202', 'title' => 'Database Management Systems', 'units' => 4.00],
            ['code' => 'CS301', 'title' => 'Software Engineering', 'units' => 3.00],
            ['code' => 'CS302', 'title' => 'Advanced Algorithms', 'units' => 3.00],
            ['code' => 'CS303', 'title' => 'Object-Oriented Programming', 'units' => 3.00],
            ['code' => 'CS304', 'title' => 'Mobile App Development', 'units' => 3.00],
        ];

        foreach ($courseData as $data) {
            $course = Course::firstOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['instructor_id' => $instructor->id])
            );
            $courses[] = $course;
        }

        // Create comprehensive learning materials for all courses
        $courseMap = collect($courses)->keyBy('code')->mapWithKeys(fn ($course, $code) => [$code => $course->id])->toArray();

        $materialsData = [
            // CS101 materials
            ['course_code' => 'CS101', 'title' => 'Welcome and Syllabus', 'description' => 'Course overview and requirements', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 1],
            ['course_code' => 'CS101', 'title' => 'Variables and Data Types', 'description' => 'Video lesson on basics', 'format' => 'video', 'difficulty_level' => 'easy', 'order_index' => 2],
            ['course_code' => 'CS101', 'title' => 'Control Flow and Loops', 'description' => 'Interactive module on conditionals', 'format' => 'video', 'difficulty_level' => 'easy', 'order_index' => 3],
            ['course_code' => 'CS101', 'title' => 'Functions and Scope', 'description' => 'Comprehensive guide with examples', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 4],
            // CS102 materials
            ['course_code' => 'CS102', 'title' => 'Arrays and Lists', 'description' => 'Introduction to linear structures', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 1],
            ['course_code' => 'CS102', 'title' => 'Linked Lists Implementation', 'description' => 'Video tutorial with live coding', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 2],
            ['course_code' => 'CS102', 'title' => 'Trees and Graphs', 'description' => 'Advanced data structures guide', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 3],
            ['course_code' => 'CS102', 'title' => 'Sorting Algorithms', 'description' => 'Complete algorithm reference', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 4],
            // CS201 materials
            ['course_code' => 'CS201', 'title' => 'HTML & CSS Basics', 'description' => 'Frontend fundamentals', 'format' => 'video', 'difficulty_level' => 'easy', 'order_index' => 1],
            ['course_code' => 'CS201', 'title' => 'Responsive Design', 'description' => 'Mobile-first approach guide', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 2],
            ['course_code' => 'CS201', 'title' => 'JavaScript Essentials', 'description' => 'Client-side scripting tutorial', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 3],
            // CS202 materials
            ['course_code' => 'CS202', 'title' => 'Database Design', 'description' => 'Conceptual and logical models', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 1],
            ['course_code' => 'CS202', 'title' => 'SQL Fundamentals', 'description' => 'Query language tutorial', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 2],
            // CS301 materials
            ['course_code' => 'CS301', 'title' => 'SDLC Methodologies', 'description' => 'Development life cycle approaches', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 1],
            ['course_code' => 'CS301', 'title' => 'Design Patterns', 'description' => 'Reusable solutions guide', 'format' => 'video', 'difficulty_level' => 'hard', 'order_index' => 2],
            // CS302 materials
            ['course_code' => 'CS302', 'title' => 'Algorithm Complexity Analysis', 'description' => 'Big O notation explained', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 1],
            ['course_code' => 'CS302', 'title' => 'Dynamic Programming', 'description' => 'Advanced techniques tutorial', 'format' => 'video', 'difficulty_level' => 'hard', 'order_index' => 2],
            // CS303 materials
            ['course_code' => 'CS303', 'title' => 'OOP Principles', 'description' => 'Encapsulation, inheritance, polymorphism', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 1],
            ['course_code' => 'CS303', 'title' => 'Class Design and Relationships', 'description' => 'UML and class hierarchies', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 2],
            // CS304 materials
            ['course_code' => 'CS304', 'title' => 'Mobile Development Framework', 'description' => 'Getting started guide', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 1],
            ['course_code' => 'CS304', 'title' => 'Building User Interfaces', 'description' => 'UI/UX best practices', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 2],
        ];

        foreach ($materialsData as $attrs) {
            $courseId = $courseMap[$attrs['course_code']];
            $materialAttributes = array_merge($attrs, ['course_id' => $courseId]);
            unset($materialAttributes['course_code']);

            LearningMaterial::firstOrCreate(
                ['course_id' => $courseId, 'title' => $attrs['title']],
                $materialAttributes
            );
        }

        // Create multiple students with enrollments for better dashboard metrics
        $students = [];
        $studentNames = [
            'Maria Garcia', 'Juan Delgado', 'Ana Reyes', 'Carlos Santos', 'Rosa Mendoza',
            'Luis Rodriguez', 'Elena Torres', 'Miguel Herrera', 'Sofia Ramirez', 'Diego Morales',
            'Isabella Cruz', 'Antonio Ortiz', 'Gabriela Ruiz', 'Fernando Gutierrez', 'Carmen Flores',
            'Victor Moreno', 'Valentina Vargas', 'Ricardo Perez', 'Alejandra Hernandez', 'Andres Jimenez',
        ];

        foreach ($studentNames as $index => $name) {
            $studentUserEmail = 'student' . ($index + 1) . '@csp.edu';
            $studentUser = User::firstOrCreate(
                ['email' => $studentUserEmail],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                ]
            );
            $student_record = Student::firstOrCreate(
                ['user_id' => $studentUser->id],
                [
                    'student_number' => '2024-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'program' => 'BS Computer Science',
                    'year_level' => rand(1, 4),
                    'admission_date' => now()->subMonths(rand(3, 24)),
                    'status' => 'active',
                ]
            );
            $students[] = $student_record;
        }

        // Add the original student if not already in the array
        $originalStudent = Student::where('student_number', '2024-0001')->first();
        if (!$originalStudent) {
            $students[] = $student;
        }

        // Enroll students in courses (3-6 courses per student)
        foreach ($students as $std) {
            $numCourses = rand(3, 6);
            $selectedCourses = collect($courses)->random($numCourses);
            
            foreach ($selectedCourses as $course) {
                $enrollment = Enrollment::firstOrCreate(
                    [
                        'student_id' => $std->id,
                        'course_id' => $course->id,
                        'semester' => '1st',
                        'school_year' => '2024-2025',
                    ],
                    ['status' => 'enrolled']
                );

                // Add grades
                Grade::updateOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    [
                        'midterm_grade' => rand(75, 98),
                        'final_grade' => rand(76, 99)
                    ]
                );
            }
        }

        // Add comprehensive learning progress data with high completion rates
        $allStudents = Student::all();
        $allMaterials = LearningMaterial::all();

        foreach ($allStudents as $std) {
            foreach ($allMaterials as $material) {
                // Only create progress for enrolled courses
                $isEnrolled = Enrollment::where('student_id', $std->id)
                    ->where('course_id', $material->course_id)
                    ->where('status', 'enrolled')
                    ->exists();

                if ($isEnrolled) {
                    // 75% chance of high completion, 25% in progress
                    $progressPercent = rand(1, 100) <= 75 ? rand(85, 100) : rand(30, 84);
                    
                    LearningProgress::updateOrCreate(
                        ['student_id' => $std->id, 'material_id' => $material->id],
                        [
                            'progress_percent' => $progressPercent,
                            'time_spent_minutes' => rand(20, 240),
                            'completed_at' => $progressPercent === 100 ? now()->subDays(rand(0, 15)) : null,
                            'updated_at' => now()->subDays(rand(0, 10)),
                        ]
                    );
                }
            }
        }
    }
}
