<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            $table->string('theme_color', 7)->default('#0d6efd')->after('learning_focus');
            $table->boolean('show_quick_links')->default(true)->after('theme_color');
            $table->boolean('show_my_info')->default(true)->after('show_quick_links');
        });
    }

    public function down(): void
    {
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            $table->dropColumn(['theme_color', 'show_quick_links', 'show_my_info']);
        });
    }
};
