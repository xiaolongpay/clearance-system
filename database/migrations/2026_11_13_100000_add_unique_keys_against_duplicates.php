<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database itself now refuses duplicate students and duplicate clearance entries.
     * Run remove-duplicates.php --apply first, because existing duplicates would block these keys.
     */
    public function up(): void
    {
        try {
            Schema::table('clearance_items', function (Blueprint $table) {
                $table->unique(['student_id', 'department_id', 'school_year_id'], 'clearance_items_one_entry_per_office');
            });
        } catch (Throwable $e) {
            // the key already exists
        }

        try {
            Schema::table('students', function (Blueprint $table) {
                $table->unique('student_number', 'students_one_record_per_number');
            });
        } catch (Throwable $e) {
            // the key already exists
        }
    }

    public function down(): void
    {
        try {
            Schema::table('clearance_items', fn (Blueprint $table) => $table->dropUnique('clearance_items_one_entry_per_office'));
            Schema::table('students', fn (Blueprint $table) => $table->dropUnique('students_one_record_per_number'));
        } catch (Throwable $e) {
            //
        }
    }
};
