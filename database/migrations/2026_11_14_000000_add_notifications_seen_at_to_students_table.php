<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The bell on the student dashboard counts clearance results that arrived after this time.
     * Results that already exist are marked as seen, so only new results light the red dot.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'notifications_seen_at')) {
            Schema::table('students', function (Blueprint $table) {
                $table->timestamp('notifications_seen_at')->nullable();
            });

            DB::table('students')->update(['notifications_seen_at' => now()]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'notifications_seen_at')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('notifications_seen_at');
            });
        }
    }
};
