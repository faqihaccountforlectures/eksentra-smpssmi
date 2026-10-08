<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\AcademicYear;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Guru Pembina Ekstrakurikuler.
     */
    public function index()
    {
        $teacherId = Auth::id();
        $user = Auth::user()->load('teacherProfile');

        Carbon::setLocale('id');
        $todayName = Carbon::now()->isoFormat('dddd'); 

        $activeYear = AcademicYear::where('is_active', true)->first();

        // 1. Ekstrakurikuler yang dibina oleh guru ini
        $myExtracurriculars = Extracurricular::where('teacher_id', $teacherId)
            ->withCount([
                'registrations as approved_count' => function ($q) {
                    $q->where('status', 'approved');
                },
                'registrations as graded_count' => function ($q) use ($activeYear) {
                    $q->where('status', 'approved')
                      ->whereHas('grades', function ($g) use ($activeYear) {
                          if ($activeYear) {
                              $g->where('academic_year_id', $activeYear->id);
                          }
                      });
                },
                'registrations as pending_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ])
            ->get();

        $ekskulIds = $myExtracurriculars->pluck('id');

        // 2. Statistik Akumulatif
        $totalExtracurriculars = $myExtracurriculars->count();
        $totalMembers = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->count();

        $totalGraded = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->whereHas('grades', function ($g) use ($activeYear) {
                if ($activeYear) {
                    $g->where('academic_year_id', $activeYear->id);
                }
            })
            ->count();

        $totalPending = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'pending')
            ->count();

        // 3. Siswa terbaru yang bergabung
        $recentMembers = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->with(['student.studentProfile', 'extracurricular', 'grades.academicYear'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('guru.dashboard', compact(
            'user',
            'todayName',
            'activeYear',
            'myExtracurriculars',
            'totalExtracurriculars',
            'totalMembers',
            'totalGraded',
            'totalPending',
            'recentMembers'
        ));
    }
}
