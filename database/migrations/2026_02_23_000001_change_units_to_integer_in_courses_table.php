<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter the `units` column to integer. Use raw SQL for compatibility.
        DB::statement('ALTER TABLE `courses` MODIFY `units` INT NOT NULL DEFAULT 3');
    }

    public function down(): void
    {
        // Revert back to decimal(4,2)
        DB::statement('ALTER TABLE `courses` MODIFY `units` DECIMAL(4,2) NOT NULL DEFAULT 3.00');
    }
};
