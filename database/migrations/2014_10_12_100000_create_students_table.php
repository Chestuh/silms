<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_number', 50)->nullable()->unique();
            $table->string('program')->nullable();
            $table->unsignedTinyInteger('year_level')->default(1);
            $table->date('admission_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'transferred', 'graduated'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
