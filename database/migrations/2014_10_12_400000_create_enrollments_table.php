<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('semester', 20)->nullable();
            $table->string('school_year', 20)->nullable();
            $table->enum('status', ['enrolled', 'dropped', 'completed'])->default('enrolled');
            $table->timestamps();
            $table->unique(['student_id', 'course_id', 'semester', 'school_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
