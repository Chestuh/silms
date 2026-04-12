<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up()
    {
        $now = Carbon::now();

        // Create a test user for the instructor
        $userId = DB::table('users')->insertGetId([
            'name' => 'G7 Test Instructor',
            'email' => 'g7-instructor@example.test',
            'password' => bcrypt('secret'),
            'role' => 'instructor',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create instructor row with department '7'
        $instructorId = DB::table('instructors')->insertGetId([
            'user_id' => $userId,
            'department' => '7',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Insert multiple Grade 7 test courses under same instructor/department
        DB::table('courses')->insert([
            [
                'code' => 'G7-MATH-201',
                'title' => 'G7 Mathematics - Algebra',
                'units' => 3.00,
                'instructor_id' => $instructorId,
                'grade_level' => '7',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'G7-SCI-201',
                'title' => 'G7 Science - General',
                'units' => 3.00,
                'instructor_id' => $instructorId,
                'grade_level' => '7',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down()
    {
        // Remove test courses and instructor/user
        DB::table('courses')->whereIn('code', ['G7-MATH-201','G7-SCI-201'])->delete();
        $instr = DB::table('instructors')->where('department', '7')->orderByDesc('id')->first();
        if ($instr) {
            DB::table('instructors')->where('id', $instr->id)->delete();
            DB::table('users')->where('id', $instr->user_id)->delete();
        }
    }
};
