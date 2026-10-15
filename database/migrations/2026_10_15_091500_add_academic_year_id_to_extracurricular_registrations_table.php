<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('extracurricular_id')->constrained('academic_years')->cascadeOnDelete();
        });

        // Set default tahun ajaran aktif (atau pertama) untuk data pendaftaran yang sudah ada
        $activeYear = DB::table('academic_years')->where('is_active', true)->first()
            ?? DB::table('academic_years')->orderBy('id', 'asc')->first();

        if ($activeYear) {
            DB::table('extracurricular_registrations')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $activeYear->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
