<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin', 'cashier') NOT NULL DEFAULT 'student'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 32)->default('student')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin') NOT NULL DEFAULT 'student'");
        }
    }
};
