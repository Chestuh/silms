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
        Schema::create('learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('code')->unique(); // e.g., "LO-001"
            $table->string('title');
            $table->text('description');
            $table->enum('bloom_level', ['remember', 'understand', 'apply', 'analyze', 'evaluate', 'create'])->default('understand');
            $table->text('assessment_criteria')->nullable(); // JSON for how this will be assessed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('learning_outcomes');
        Schema::enableForeignKeyConstraints();
    }
};
