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
        Schema::create('assessment_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('learning_material_id')->nullable()->constrained('learning_materials')->onDelete('set null');
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            
            // Assessment details
            $table->string('title');
            $table->text('description');
            $table->enum('assessment_type', ['quiz', 'exam', 'assignment', 'project'])->default('quiz');
            $table->integer('number_of_questions')->default(0);
            $table->decimal('passing_score', 5, 2)->nullable();
            $table->integer('time_limit_minutes')->nullable();
            
            // Questions and content
            $table->longText('questions_json'); // Stores all questions in JSON format
            
            // Status and approval
            $table->enum('status', ['pending_review', 'approved', 'rejected', 'published'])->default('pending_review');
            $table->text('instructor_feedback')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            // AI metadata
            $table->text('generation_metadata')->nullable(); // Info about AI generation
            $table->integer('ai_generated')->default(1); // Boolean: was this AI-generated?
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('assessment_templates');
        Schema::enableForeignKeyConstraints();
    }
};
