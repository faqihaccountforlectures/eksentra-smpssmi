<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\AcademicYear;
use App\Models\ExtracurricularGrade;

class ExtracurricularController extends Controller
{
    /**
     * Menampilkan daftar ekstrakurikuler yang dibina oleh pembina yang sedang login.
     */
    public function index()
    {
        $pembinaId = Auth::id();
        
        $extracurriculars = Extracurricular::where('teacher_id', $pembinaId)
            ->withCount('registrations')
            ->get();
            
        return view('pembina.extracurriculars.index', compact('extracurriculars'));
    }

    /**
     * Menampilkan detail spesifik ekstrakurikuler beserta daftar siswanya dan penilaian per tahun ajaran & semester.
     *
     * @param Request $request
     * @param int $id ID ekstrakurikuler
     */
    public function show(Request $request, $id)
    {
        $pembinaId = Auth::id();
        
        $extracurricular = Extracurricular::where('teacher_id', $pembinaId)
            ->withCount('registrations')
            ->findOrFail($id);

        // Ambil seluruh daftar tahun ajaran untuk filter
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();

        // Tentukan tahun ajaran yang dipilih (default: tahun ajaran aktif atau terbaru)
        $selectedYearId = $request->query('academic_year_id');
        if ($selectedYearId) {
            $selectedYear = $academicYears->firstWhere('id', $selectedYearId) 
                ?? $academicYears->firstWhere('is_active', true) 
                ?? $academicYears->first();
        } else {
            $selectedYear = $academicYears->firstWhere('is_active', true) 
                ?? $academicYears->first();
        }

        $selectedYearId = $selectedYear ? $selectedYear->id : null;

        // Mengambil daftar pendaftaran siswa beserta nilai pada tahun ajaran yang dipilih
        $registrations = $extracurricular->registrations()
            ->with([
                'student.studentProfile',
                'grades' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                }
            ])
            ->orderByRaw("FIELD(status, 'approved', 'pending', 'rejected')")
            ->paginate(10)
            ->appends(['academic_year_id' => $selectedYearId]);
            
        return view('pembina.extracurriculars.show', compact(
            'extracurricular', 
            'registrations', 
            'academicYears', 
            'selectedYear'
        ));
    }

    /**
     * Memproses penyimpanan / pembaruan nilai ekstrakurikuler siswa untuk Semester Ganjil dan Genap.
     * Hanya siswa yang berstatus 'approved' yang dapat dinilai oleh guru pembina.
     */
    public function updateGrade(Request $request, $id, $registrationId)
    {
        $pembinaId = Auth::id();
        $extracurricular = Extracurricular::where('teacher_id', $pembinaId)->findOrFail($id);
        
        $registration = $extracurricular->registrations()
            ->where('status', 'approved')
            ->findOrFail($registrationId);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            
            // Semester Ganjil
            'ganjil_grade' => 'nullable|string|in:A,B,C,D',
            'ganjil_score' => 'nullable|integer|min:0|max:100',
            'ganjil_notes' => 'nullable|string|max:1000',

            // Semester Genap
            'genap_grade' => 'nullable|string|in:A,B,C,D',
            'genap_score' => 'nullable|integer|min:0|max:100',
            'genap_notes' => 'nullable|string|max:1000',
        ], [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'ganjil_grade.in' => 'Predikat nilai semester ganjil harus A, B, C, atau D.',
            'genap_grade.in' => 'Predikat nilai semester genap harus A, B, C, atau D.',
            'ganjil_score.min' => 'Nilai angka semester ganjil minimal 0.',
            'ganjil_score.max' => 'Nilai angka semester ganjil maksimal 100.',
            'genap_score.min' => 'Nilai angka semester genap minimal 0.',
            'genap_score.max' => 'Nilai angka semester genap maksimal 100.',
        ]);

        $academicYear = AcademicYear::findOrFail($validated['academic_year_id']);

        // 1. Simpan/Update Semester Ganjil
        if (!empty($validated['ganjil_grade'])) {
            ExtracurricularGrade::updateOrCreate(
                [
                    'registration_id' => $registration->id,
                    'academic_year_id' => $academicYear->id,
                    'semester' => 'ganjil',
                ],
                [
                    'grade' => $validated['ganjil_grade'],
                    'score' => $validated['ganjil_score'] ?? null,
                    'notes' => $validated['ganjil_notes'] ?? null,
                ]
            );
        } else {
            // Jika dikosongkan, hapus penilaian ganjil untuk tahun ajaran ini
            ExtracurricularGrade::where([
                'registration_id' => $registration->id,
                'academic_year_id' => $academicYear->id,
                'semester' => 'ganjil',
            ])->delete();
        }

        // 2. Simpan/Update Semester Genap
        if (!empty($validated['genap_grade'])) {
            ExtracurricularGrade::updateOrCreate(
                [
                    'registration_id' => $registration->id,
                    'academic_year_id' => $academicYear->id,
                    'semester' => 'genap',
                ],
                [
                    'grade' => $validated['genap_grade'],
                    'score' => $validated['genap_score'] ?? null,
                    'notes' => $validated['genap_notes'] ?? null,
                ]
            );
        } else {
            // Jika dikosongkan, hapus penilaian genap untuk tahun ajaran ini
            ExtracurricularGrade::where([
                'registration_id' => $registration->id,
                'academic_year_id' => $academicYear->id,
                'semester' => 'genap',
            ])->delete();
        }

        return redirect()->back()->with('success', "Nilai ekstrakurikuler tahun ajaran {$academicYear->year_name} (Semester Ganjil & Genap) berhasil disimpan!");
    }
}
