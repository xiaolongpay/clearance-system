<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Signature images are kept in the database instead of on a disk.
     */
    public function up(): void
    {
        if (Schema::hasTable('department_signatures')) {
            return;
        }

        Schema::create('department_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->unique();
            $table->string('mime');
            $table->longText('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_signatures');
    }
};
