<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            // Offices that only need physical items can switch this off
            $table->boolean('accepts_uploads')->default(true);

            // When the office last opened its notification bell
            $table->timestamp('uploads_seen_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['accepts_uploads', 'uploads_seen_at']);
        });
    }
};
