<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_path_rules', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // course_prerequisite, material_prerequisite, difficulty_order
            $table->string('name', 255)->nullable();
            $table->foreignId('source_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('target_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->unsignedBigInteger('source_material_id')->nullable();
            $table->unsignedBigInteger('target_material_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('source_material_id')->references('id')->on('learning_materials')->nullOnDelete();
            $table->foreign('target_material_id')->references('id')->on('learning_materials')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_rules');
    }
};
