<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearance_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clearance_item_id')->constrained('clearance_items')->cascadeOnDelete();
            $table->string('kind', 10);                      // file or link
            $table->string('original_name')->nullable();
            $table->string('mime', 120)->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->longText('data')->nullable();            // the file, base64 (Vercel has no disk)
            $table->string('url', 500)->nullable();          // for video links
            $table->timestamp('viewed_at')->nullable();      // set when the office opens it
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_uploads');
    }
};
