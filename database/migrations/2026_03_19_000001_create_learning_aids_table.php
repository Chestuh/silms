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
        Schema::create('learning_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('learning_materials')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->enum('aid_type', ['summary', 'quiz', 'flashcard', 'reviewer', 'study_guide'])->default('summary');
            $table->text('content');
            $table->text('metadata')->nullable(); // JSON data for storing quiz questions, flashcard pairs, etc
            $table->integer('generation_tokens_used')->default(0); // Track API usage
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('last_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('learning_aids');
        Schema::enableForeignKeyConstraints();
    }
};
