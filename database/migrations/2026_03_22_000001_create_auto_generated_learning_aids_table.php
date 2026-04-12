<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_learning_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('learning_materials')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->timestamp('release_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'available', 'expired'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_learning_aids');
    }
};
