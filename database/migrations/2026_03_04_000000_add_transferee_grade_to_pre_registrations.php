<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registrations', 'transferee_grade')) {
                $table->string('transferee_grade')->nullable()->after('applicant_category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'transferee_grade')) {
                $table->dropColumn('transferee_grade');
            }
        });
    }
};
