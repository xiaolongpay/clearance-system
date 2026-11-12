<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "Keep me signed in on this device" needs a place to store the remember token.
     * Staff accounts already have one; student accounts did not.
     */
    public function up(): void
    {
        if (Schema::hasTable('students') && ! Schema::hasColumn('students', 'remember_token')) {
            Schema::table('students', function (Blueprint $table) {
                $table->rememberToken();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'remember_token')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
