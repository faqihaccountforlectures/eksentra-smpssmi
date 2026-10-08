<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus tabel yang tidak lagi digunakan karena sistem difokuskan murni untuk Portal Ekstrakurikuler.
     */
    public function up(): void
    {
        Schema::dropIfExists('learning_materials');
        Schema::dropIfExists('lesson_schedules');
        Schema::dropIfExists('teacher_assignments');
        Schema::dropIfExists('subjects');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversal intentionally left empty as system has migrated away from academic schedules
    }
};
