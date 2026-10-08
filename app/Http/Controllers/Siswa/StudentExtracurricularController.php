<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;

class StudentExtracurricularController extends Controller
{
    /**
     * Menampilkan katalog ekstrakurikuler untuk siswa.
     * Mengambil daftar semua ekskul dan mengecek status pendaftaran siswa yang sedang login.
     */
    public function index()
    {
        // Ambil semua daftar ekskul yang tersedia beserta nama guru pembinanya
        $extracurriculars = Extracurricular::with('teacher.teacherProfile')->get();
        
        // Ambil riwayat pendaftaran siswa saat ini beserta nilai per tahun ajaran & semester
        // Lalu jadikan id ekskul sebagai kunci (key)
        $myRegistrations = ExtracurricularRegistration::with(['grades.academicYear'])
                            ->where('student_id', auth()->id())
                            ->get()
                            ->keyBy('extracurricular_id');

        return view('siswa.extracurriculars.index', compact('extracurriculars', 'myRegistrations'));
    }
}
