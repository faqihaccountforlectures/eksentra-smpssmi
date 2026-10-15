<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\AcademicYear;
use App\Models\ExtracurricularGrade;
use App\Models\ClassRoom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ExtracurricularController extends Controller
{
    /**
     * Menampilkan daftar ekstrakurikuler yang dibina oleh coach yang sedang login.
     */
    public function index()
    {
        $coachId = Auth::id();
        $activeYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::latest('id')->first();
        
        $extracurriculars = Extracurricular::where('coach_id', $coachId)
            ->withCount([
                'registrations' => function ($q) use ($activeYear) {
                    if ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id);
                    }
                }
            ])
            ->get();
            
        return view('coach.extracurriculars.index', compact('extracurriculars', 'activeYear'));
    }

    /**
     * Menampilkan detail spesifik ekstrakurikuler beserta daftar siswanya dan penilaian per tahun ajaran, kelas & semester.
     *
     * @param Request $request
     * @param int $id ID ekstrakurikuler
     */
    public function show(Request $request, $id)
    {
        $coachId = Auth::id();

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

        // Ambil data ekskul beserta hitungan pendaftar khusus di tahun ajaran yang dipilih
        $extracurricular = Extracurricular::where('coach_id', $coachId)
            ->withCount([
                'registrations as selected_year_registrations_count' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                }
            ])
            ->findOrFail($id);

        // Ambil seluruh daftar kelas untuk filter
        $classes = ClassRoom::orderBy('grade_level')->orderBy('name')->get();
        $selectedClassId = $request->query('class_id');

        // Mengambil daftar pendaftaran siswa khusus pada tahun ajaran yang dipilih
        $registrationsQuery = $extracurricular->registrations()
            ->when($selectedYearId, function ($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            })
            ->with([
                'student.studentProfile',
                'student.classEnrollments' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                    $q->with('classRoom');
                },
                'grades' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                }
            ]);

        // Filter berdasarkan kelas siswa jika dipilih
        if ($selectedClassId) {
            $registrationsQuery->whereHas('student.classEnrollments', function ($q) use ($selectedClassId, $selectedYearId) {
                $q->where('class_room_id', $selectedClassId);
                if ($selectedYearId) {
                    $q->where('academic_year_id', $selectedYearId);
                }
            });
        }

        $registrations = $registrationsQuery
            ->orderByRaw("FIELD(status, 'approved', 'pending', 'rejected')")
            ->paginate(10)
            ->appends([
                'academic_year_id' => $selectedYearId,
                'class_id' => $selectedClassId,
            ]);
            
        return view('coach.extracurriculars.show', compact(
            'extracurricular', 
            'registrations', 
            'academicYears', 
            'selectedYear',
            'classes',
            'selectedClassId'
        ));
    }

    /**
     * Cetak PDF Laporan Penilaian untuk Ekstrakurikuler ini.
     */
    public function exportPdf(Request $request, $id)
    {
        $coachId = Auth::id();
        $extracurricular = Extracurricular::where('coach_id', $coachId)->findOrFail($id);

        $selectedYearId = $request->query('academic_year_id');
        if ($selectedYearId) {
            $selectedYear = AcademicYear::find($selectedYearId);
        } else {
            $selectedYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::latest('id')->first();
        }
        $selectedYearId = $selectedYear?->id;

        $selectedClassId = $request->query('class_id');
        $selectedClass = $selectedClassId ? ClassRoom::find($selectedClassId) : null;

        $query = $extracurricular->registrations()
            ->when($selectedYearId, function ($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            })
            ->with([
                'student.studentProfile',
                'student.classEnrollments' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                    $q->with('classRoom');
                },
                'grades' => function ($q) use ($selectedYearId) {
                    if ($selectedYearId) {
                        $q->where('academic_year_id', $selectedYearId);
                    }
                }
            ]);

        if ($selectedClassId) {
            $query->whereHas('student.classEnrollments', function ($q) use ($selectedClassId, $selectedYearId) {
                $q->where('class_room_id', $selectedClassId);
                if ($selectedYearId) {
                    $q->where('academic_year_id', $selectedYearId);
                }
            });
        }

        $registrations = $query->orderByRaw("FIELD(status, 'approved', 'pending', 'rejected')")->get();
        $coachUser = Auth::user();

        $pdf = Pdf::loadView('coach.extracurriculars.report-single-pdf', [
            'extracurricular' => $extracurricular,
            'registrations'   => $registrations,
            'selectedYear'    => $selectedYear,
            'selectedClass'   => $selectedClass,
            'coachUser'       => $coachUser,
            'printDate'       => now()->translatedFormat('d F Y'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        $safeName = Str::slug($extracurricular->name);
        return $pdf->download('laporan-nilai-' . $safeName . '-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Memproses penyimpanan / pembaruan nilai ekstrakurikuler siswa untuk Semester Ganjil dan Genap.
     * Hanya siswa yang berstatus 'approved' yang dapat dinilai oleh coach.
     */
    public function updateGrade(Request $request, $id, $registrationId)
    {
        $coachId = Auth::id();
        $extracurricular = Extracurricular::where('coach_id', $coachId)->findOrFail($id);
        
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
