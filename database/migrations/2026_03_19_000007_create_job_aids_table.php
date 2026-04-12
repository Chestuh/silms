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
        Schema::create('job_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            
            // Content
            $table->enum('aid_type', ['study_guide', 'tutorial', 'career_guidance', 'skill_guide', 'reference'])->default('study_guide');
            $table->longText('content');
            $table->text('metadata')->nullable(); // JSON for storing structure, resources, etc
            
            // Relevance
            $table->string('topic_focus');
            $table->text('career_connections')->nullable(); // JSON array of related careers/skills
            $table->decimal('relevance_score', 3, 2)->nullable(); // How relevant to student's performance
            
            // Usage tracking
            $table->integer('views')->default(0);
            $table->integer('useful_count')->default(0); // Students marking as useful
            $table->timestamp('last_viewed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('job_aids');
        Schema::enableForeignKeyConstraints();
    }
};
