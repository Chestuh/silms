<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            $table->enum('completion_status', ['pending', 'in_progress', 'completed'])->default('pending')->after('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            $table->dropColumn('completion_status');
        });
    }
};
