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
        Schema::create('student_progress_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            
            // Progress metrics
            $table->decimal('completion_rate', 5, 2)->default(0); // 0-100
            $table->integer('materials_completed')->default(0);
            $table->integer('materials_total')->default(0);
            $table->integer('total_time_minutes')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable(); // Average material rating
            
            // Grade metrics
            $table->decimal('current_grade', 5, 2)->nullable();
            $table->decimal('quiz_average', 5, 2)->nullable();
            $table->decimal('assessment_average', 5, 2)->nullable();
            
            // Risk assessment
            $table->enum('at_risk_status', ['on_track', 'at_risk', 'critical'])->default('on_track');
            $table->text('weak_topics')->nullable(); // JSON array of weak topics
            $table->text('strong_topics')->nullable(); // JSON array of strong topics
            $table->text('recommendations')->nullable(); // AI-generated recommendations
            
            // Timestamps
            $table->timestamp('last_analyzed_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('student_progress_analytics');
        Schema::enableForeignKeyConstraints();
    }
};
