<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\LearningMaterial;
use Illuminate\Console\Command;

class GenerateLearningMaterials extends Command
{
    protected $signature = 'materials:generate {--dry-run : Show what would be created without creating}';
    protected $description = 'Generate learning materials for all year level courses';

    public function handle(): int
    {
        $gradeLevels = ['7', '8', '9', '10', '11', '12'];
        $dryRun = $this->option('dry-run');

        $this->info('Generating learning materials for all year level courses...');
        $this->newLine();

        $totalMaterials = 0;
        $coursesProcessed = 0;

        foreach ($gradeLevels as $grade) {
            $courses = Course::where('grade_level', $grade)->get();

            if ($courses->isEmpty()) {
                $this->warn("No courses found for Grade {$grade}");
                continue;
            }

            $this->info("Processing Grade {$grade}: {$courses->count()} course(s)");

            foreach ($courses as $course) {
                $materialsCreated = $this->createMaterialsForCourse($course, $dryRun);
                $totalMaterials += $materialsCreated;
                $coursesProcessed++;

                if ($materialsCreated > 0) {
                    $this->line("  ✓ {$course->code}: {$materialsCreated} material(s) " . ($dryRun ? 'would be' : 'were') . " created");
                }
            }
        }

        $this->newLine();
        $this->info("Summary: {$coursesProcessed} courses processed, {$totalMaterials} material(s) " . ($dryRun ? 'would be' : 'were') . " created");

        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to actually create materials.');
        }

        return Command::SUCCESS;
    }

    protected function createMaterialsForCourse(Course $course, bool $dryRun): int
    {
        // Define materials based on grade level
        $materialsData = $this->getMaterialsForGradeLevel($course->grade_level, $course->code);

        if ($dryRun) {
            return count($materialsData);
        }

        $created = 0;
        foreach ($materialsData as $index => $materialData) {
            // Check if material already exists
            $exists = LearningMaterial::where('course_id', $course->id)
                ->where('title', $materialData['title'])
                ->exists();

            if (!$exists) {
                LearningMaterial::create([
                    'course_id' => $course->id,
                    'title' => $materialData['title'],
                    'description' => $materialData['description'],
                    'format' => $materialData['format'],
                    'difficulty_level' => $materialData['difficulty_level'],
                    'order_index' => $materialData['order_index'],
                    'approval_status' => 'approved',
                    'completion_status' => 'pending',
                ]);
                $created++;
            }
        }

        return $created;
    }

    protected function getMaterialsForGradeLevel(string $grade, string $courseCode): array
    {
        // Common materials for all grade levels
        $commonMaterials = [
            ['title' => 'Course Syllabus and Overview', 'description' => 'Course requirements, objectives, and grading policy', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 1],
            ['title' => 'Weekly Lesson Plans', 'description' => 'Detailed lesson plans for each topic', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 2],
            ['title' => 'Supplementary Reading Materials', 'description' => 'Additional reading resources and references', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 3],
            ['title' => 'Practice Exercises', 'description' => 'Exercises to reinforce learning', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 4],
            ['title' => 'Video Tutorials', 'description' => 'Video lessons covering key concepts', 'format' => 'video', 'difficulty_level' => 'medium', 'order_index' => 5],
            ['title' => 'Quiz Assessment', 'description' => 'Online quiz to test understanding', 'format' => 'quiz', 'difficulty_level' => 'medium', 'order_index' => 6],
            ['title' => 'Final Project Guidelines', 'description' => 'Instructions for the final course project', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 7],
            ['title' => 'Reference Links', 'description' => 'Curated external resources for further study', 'format' => 'link', 'difficulty_level' => 'easy', 'order_index' => 8],
        ];

        // Grade-specific additional materials
        $gradeSpecificMaterials = [];

        switch ($grade) {
            case '7':
                $gradeSpecificMaterials = [
                    ['title' => 'Introduction to the Subject', 'description' => 'Basic introduction and foundational concepts', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 9],
                    ['title' => 'Beginner Activities', 'description' => 'Hands-on activities for beginners', 'format' => 'document', 'difficulty_level' => 'easy', 'order_index' => 10],
                ];
                break;
            case '8':
                $gradeSpecificMaterials = [
                    ['title' => 'Intermediate Concepts', 'description' => 'Building on foundational knowledge', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 9],
                    ['title' => 'Application Exercises', 'description' => 'Real-world application exercises', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 10],
                ];
                break;
            case '9':
                $gradeSpecificMaterials = [
                    ['title' => 'Advanced Topics Introduction', 'description' => 'Introduction to advanced subject matter', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 9],
                    ['title' => 'Research Project Guide', 'description' => 'Guide for conducting research projects', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 10],
                ];
                break;
            case '10':
                $gradeSpecificMaterials = [
                    ['title' => 'Comprehensive Review', 'description' => 'Complete review of all topics', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 9],
                    ['title' => 'Capstone Project', 'description' => 'Final capstone project requirements', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 10],
                    ['title' => 'College Prep Materials', 'description' => 'Preparation materials for higher education', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 11],
                ];
                break;
            case '11':
                $gradeSpecificMaterials = [
                    ['title' => 'Specialized Module 1', 'description' => 'First specialized topic module', 'format' => 'document', 'difficulty_level' => 'medium', 'order_index' => 9],
                    ['title' => 'Specialized Module 2', 'description' => 'Second specialized topic module', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 10],
                    ['title' => 'Industry Connections', 'description' => 'Links to industry practices and professionals', 'format' => 'link', 'difficulty_level' => 'hard', 'order_index' => 11],
                    ['title' => 'Senior Project Proposal', 'description' => 'Template and guidelines for senior project', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 12],
                ];
                break;
            case '12':
                $gradeSpecificMaterials = [
                    ['title' => 'Advanced Specialization', 'description' => 'In-depth specialization content', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 9],
                    ['title' => 'Internship Guidelines', 'description' => 'Requirements and guidelines for internship', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 10],
                    ['title' => 'Thesis/ capstone Writing Guide', 'description' => 'Comprehensive guide for thesis writing', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 11],
                    ['title' => 'Career Preparation', 'description' => 'Resume, interview, and career readiness materials', 'format' => 'document', 'difficulty_level' => 'hard', 'order_index' => 12],
                    ['title' => 'Alumni Network Resources', 'description' => 'Resources for connecting with alumni', 'format' => 'link', 'difficulty_level' => 'medium', 'order_index' => 13],
                ];
                break;
        }

        return array_merge($commonMaterials, $gradeSpecificMaterials);
    }
}