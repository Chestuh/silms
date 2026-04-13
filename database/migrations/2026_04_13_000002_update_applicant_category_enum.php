<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `pre_registrations` MODIFY `applicant_category` ENUM('grade7','grade8','grade9','grade10','grade11','grade12','transferee','returnee') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `pre_registrations` MODIFY `applicant_category` ENUM('grade7','grade11','grade12','transferee','returnee') NULL");
    }
};
