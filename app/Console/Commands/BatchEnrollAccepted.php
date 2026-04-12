<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PreRegistration;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class BatchEnrollAccepted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:enroll {--student= : Student ID to process} {--semester= : Semester to use} {--school-year= : School year to use} {--force : Actually create enrollments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch-enroll approved pre-registrations (dry-run by default). Use --force to apply.';

    public function handle(): int
    {
        $studentId = $this->option('student');
        $semester = $this->option('semester') ?? $this->guessSemester();
        $schoolYear = $this->option('school-year') ?? $this->guessSchoolYear();
        $doIt = $this->option('force') ? true : false;

        $this->info('Semester: '.$semester.' | School year: '.$schoolYear.' | Apply: '.($doIt ? 'yes' : 'no'));

        $preRegs = [];
        if ($studentId) {
            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                $this->error('Student not found: '.$studentId);
                return 1;
            }
            $user = $student->user;
            $pr = PreRegistration::where('email', $user->email)->first();
            if (!$pr) {
                $this->error('No pre-registration found for user '.$user->email);
                return 1;
            }
            $preRegs = [$pr];
        } else {
            $preRegs = PreRegistration::where('status', 'approved')->get();
        }

        $map = [
            'grade7' => '7',
            'grade11' => '11',
            'grade12' => '12',
        ];

        $totalCreated = 0;

        foreach ($preRegs as $pr) {
            $user = User::where('email', $pr->email)->first();
            if (!$user || !$user->student) {
                $this->line('Skipping '.$pr->email.' (no user/student)');
                continue;
            }
            $student = $user->student;

            $appCat = $pr->applicant_category ?? null;
            if (!$appCat || !isset($map[$appCat])) {
                $this->line('Skipping '.$pr->email.' (unknown applicant_category)');
                continue;
            }
            $grade = $map[$appCat];

            $courses = Course::where('grade_level', $grade)->get();
            if ($courses->isEmpty()) {
                $this->line('No courses for grade '.$grade.' ('.$pr->email.')');
                continue;
            }

            $this->line('Processing '.$pr->email.' -> Grade '.$grade.' ('.count($courses).' courses)');

            foreach ($courses as $course) {
                $exists = Enrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->where('semester', $semester)
                    ->where('school_year', $schoolYear)
                    ->exists();

                if ($exists) {
                    $this->line('  already enrolled: '.$course->code);
                    continue;
                }

                if ($doIt) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'semester' => $semester,
                        'school_year' => $schoolYear,
                        'status' => 'enrolled',
                    ]);
                    $this->line('  created: '.$course->code);
                    $totalCreated++;
                } else {
                    $this->line('  would create: '.$course->code);
                }
            }
        }

        $this->info('Done. Total created: '.$totalCreated);
        if (!$doIt) {
            $this->info('Run with --force to actually create enrollments.');
        }

        return 0;
    }

    protected function guessSchoolYear(): string
    {
        $m = (int) date('n');
        $y = (int) date('Y');
        $start = $m < 6 ? $y - 1 : $y;
        return $start.'-'.($start + 1);
    }

    protected function guessSemester(): string
    {
        // Simple heuristic
        $m = (int) date('n');
        return ($m >= 6 && $m <= 10) ? '1st' : '2nd';
    }
}
