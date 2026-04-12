<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credential_requests', function (Blueprint $table) {
            $table->timestamp('payment_cleared_at')->nullable()->after('letter_path');
        });
    }

    public function down(): void
    {
        Schema::table('credential_requests', function (Blueprint $table) {
            $table->dropColumn('payment_cleared_at');
        });
    }
};
