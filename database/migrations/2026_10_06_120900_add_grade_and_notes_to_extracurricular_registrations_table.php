<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->string('grade', 10)->nullable()->after('status')->comment('Predikat nilai misal: A, B, C, D');
            $table->unsignedInteger('score')->nullable()->after('grade')->comment('Nilai angka misal 0-100');
            $table->text('notes')->nullable()->after('score')->comment('Catatan pembina / evaluasi perkembangan');
            $table->timestamp('graded_at')->nullable()->after('notes')->comment('Waktu penginputan nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->dropColumn(['grade', 'score', 'notes', 'graded_at']);
        });
    }
};
