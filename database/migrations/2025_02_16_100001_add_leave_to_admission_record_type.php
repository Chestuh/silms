<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE admission_records MODIFY COLUMN record_type ENUM('admission', 'transfer', 'readmission', 'leave') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE admission_records MODIFY COLUMN record_type ENUM('admission', 'transfer', 'readmission') NOT NULL");
        }
    }
};
