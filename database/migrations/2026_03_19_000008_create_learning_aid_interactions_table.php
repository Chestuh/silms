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
        Schema::create('learning_aid_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('learning_aid_id')->constrained('learning_aids')->onDelete('cascade');
            $table->enum('interaction_type', ['view', 'bookmark', 'flag_difficult', 'share', 'request_more'])->default('view');
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('quiz_score')->nullable(); // If they took the quiz
            $table->text('notes')->nullable(); // Student's personal notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('learning_aid_interactions');
        Schema::enableForeignKeyConstraints();
    }
};
