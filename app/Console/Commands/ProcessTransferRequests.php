<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransferRequest;

class ProcessTransferRequests extends Command
{
    protected $signature = 'transfer:process {--approve : Approve all pending} {--reject : Reject all pending}';
    protected $description = 'Process transfer requests (approve/reject)';

    public function handle(): int
    {
        $pending = TransferRequest::where('status', 'pending')->get();
        if ($pending->isEmpty()) {
            $this->info('No pending transfer requests.');
            return 0;
        }

        $this->info('Found '.$pending->count().' pending transfer requests');

        if ($this->option('approve')) {
            foreach ($pending as $p) {
                $p->status = 'approved';
                $p->processed_at = now();
                $p->save();
                $this->line('Approved #'.$p->id.' (student_id: '.$p->student_id.')');
            }
            return 0;
        }

        if ($this->option('reject')) {
            foreach ($pending as $p) {
                $p->status = 'rejected';
                $p->processed_at = now();
                $p->save();
                $this->line('Rejected #'.$p->id.' (student_id: '.$p->student_id.')');
            }
            return 0;
        }

        foreach ($pending as $p) {
            $this->line('#'.$p->id.' student: '.$p->student_id.' from: '.$p->from_school.' to: '.$p->to_school.' notes: '.substr($p->notes,0,80));
        }

        $this->info('Run with --approve or --reject to process all pending requests.');
        return 0;
    }
}
