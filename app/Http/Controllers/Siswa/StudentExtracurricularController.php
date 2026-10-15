<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class StudentExtracurricularController extends Controller
{
    /**
     * Menampilkan katalog ekstrakurikuler untuk siswa.
     * Mengambil daftar semua ekskul dan mengecek status pendaftaran siswa pada tahun ajaran aktif.
     */
    public function index()
    {
        // Ambil semua daftar ekskul yang tersedia beserta nama guru pembinanya
        $extracurriculars = Extracurricular::with('coach.coachProfile')->get();
        
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Ambil riwayat pendaftaran siswa pada tahun ajaran aktif beserta nilai per semester
        // Lalu jadikan id ekskul sebagai kunci (key)
        $myRegistrations = ExtracurricularRegistration::with(['grades.academicYear'])
                            ->where('student_id', auth()->id())
                            ->when($activeYear, function ($q) use ($activeYear) {
                                $q->where('academic_year_id', $activeYear->id);
                            })
                            ->get()
                            ->keyBy('extracurricular_id');

        return view('siswa.extracurriculars.index', compact('extracurriculars', 'myRegistrations', 'activeYear'));
    }
}
