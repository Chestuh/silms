<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curriculum_standards', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., "STEM-01", "ENG-02"
            $table->string('title');
            $table->text('description');
            $table->string('subject_area');
            $table->string('grade_level')->nullable();
            $table->text('competencies')->nullable(); // JSON array of competencies
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('curriculum_standards');
        Schema::enableForeignKeyConstraints();
    }
};
