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
        Schema::create('course_curriculum_alignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('learning_material_id')->constrained('learning_materials')->onDelete('cascade');
            $table->foreignId('learning_outcome_id')->nullable()->constrained('learning_outcomes')->onDelete('set null');
            $table->foreignId('curriculum_standard_id')->nullable()->constrained('curriculum_standards')->onDelete('set null');
            $table->string('competency')->nullable(); // Specific competency being addressed
            $table->integer('alignment_strength')->default(1); // 1-5 scale
            $table->text('alignment_notes')->nullable();
            $table->timestamps();
            
            // Use shorter constraint name to avoid MySQL identifier length limit
            $table->unique(['course_id', 'learning_material_id'], 'cca_course_material_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('course_curriculum_alignments');
        Schema::enableForeignKeyConstraints();
    }
};
