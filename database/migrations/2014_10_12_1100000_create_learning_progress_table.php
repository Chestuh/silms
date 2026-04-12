<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('learning_materials')->cascadeOnDelete();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->unsignedInteger('time_spent_minutes')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_progress');
    }
};
