<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ReminderDueNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SendDueReminders extends Command
{
    protected $signature = 'reminders:send-due';
    protected $description = 'Send in-app notifications for due study reminders';

    public function handle()
    {
        $now = Carbon::now();
        $reminders = DB::table('study_reminders')
            ->where('remind_at', '<=', $now)
            ->where('sent', false)
            ->get();

        foreach ($reminders as $reminder) {
            $student = Student::find($reminder->student_id);
            if ($student && $student->user) {
                $student->user->notify(new ReminderDueNotification($reminder));
                DB::table('study_reminders')->where('id', $reminder->id)->update(['sent' => true]);
            }
        }
        $this->info('Due reminders processed.');
    }
}
