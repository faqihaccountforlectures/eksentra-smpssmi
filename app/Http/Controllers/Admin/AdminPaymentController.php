<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminPaymentController extends Controller
{
    /**
     * Menampilkan halaman riwayat semua pembayaran ekskul.
     * Admin bisa meninjau mana yang pending dan harus diverifikasi.
     * Termasuk fitur pencarian, filter status, dan paginasi manual.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $monthFilter = $request->query('month');
        $yearFilter = $request->query('year');
        $search = $request->query('search');

        // 1. Ambil semua data pembayaran riil dari seluruh siswa
        $realPayments = Payment::with(['student.studentProfile', 'extracurricular', 'verifier'])
            ->get();

        // 2. Ambil semua registrasi yang disetujui untuk tahun ajaran aktif (untuk tagihan "Belum Lunas")
        $activeYear = AcademicYear::where('is_active', true)->first();
        $approvedRegistrations = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular', 'academicYear'])
            ->where('status', 'approved')
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->get();

        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $unpaidBills = collect();
        $currentDate = now()->startOfMonth();

        foreach ($approvedRegistrations as $reg) {
            // Abaikan jika gratis
            if ($reg->extracurricular->fee <= 0) {
                continue;
            }

            // Tentukan rentang tagihan berdasarkan tahun ajaran (Juli – Juni)
            $yearModel = $reg->academicYear ?? $activeYear;
            $yearStart = $yearModel ? $yearModel->startDate() : $reg->created_at->copy()->startOfMonth();
            $yearEnd   = $yearModel ? $yearModel->endDate()->startOfMonth() : $currentDate->copy();

            // Mulai dari yang lebih akhir: tanggal daftar vs awal tahun ajaran
            $billStart = $reg->created_at->startOfMonth()->max($yearStart);
            // Selesai di yang lebih awal: bulan ini vs akhir tahun ajaran
            $billEnd   = $currentDate->min($yearEnd);

            $iteratorDate = $billStart->copy();

            while ($iteratorDate->lte($billEnd)) {
                $monthIndex = $iteratorDate->month;
                $year       = $iteratorDate->year;
                $monthStr   = $monthsName[$monthIndex];

                // Cek apakah ada pembayaran
                $hasPaid = $realPayments->where('student_id', $reg->student_id)
                                    ->where('extracurricular_id', $reg->extracurricular_id)
                                    ->where('month', $monthStr)
                                    ->where('year', $year)
                                    ->whereIn('payment_status', ['pending', 'verified'])
                                    ->first();

                if (!$hasPaid) {
                    $dummyPayment = new Payment([
                        'student_id'         => $reg->student_id,
                        'extracurricular_id' => $reg->extracurricular_id,
                        'month'              => $monthStr,
                        'year'               => $year,
                        'total_amount'       => $reg->extracurricular->fee,
                        'payment_status'     => 'unpaid'
                    ]);

                    $dummyPayment->setRelation('extracurricular', $reg->extracurricular);
                    $dummyPayment->setRelation('student', $reg->student);
                    $dummyPayment->created_at = $iteratorDate->copy();

                    $unpaidBills->push($dummyPayment);
                }

                $iteratorDate->addMonth();
            }
        }

        // 3. Gabungkan pembayaran riil dan tagihan virtual
        $allTransactions = $unpaidBills->concat($realPayments);

        // 4. Proses Filter Bulan & Tahun Ajaran
        if ($monthFilter) {
            $allTransactions = $allTransactions->where('month', $monthFilter);
        }

        if ($yearFilter && str_contains($yearFilter, '/')) {
            // yearFilter = "2026/2027" → Juli 2026 s/d Juni 2027
            [$fyStart, $fyEnd] = explode('/', $yearFilter);
            $fyMonthsInStartYear = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $fyMonthsInEndYear   = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            $allTransactions = $allTransactions->filter(function ($p) use ($fyStart, $fyEnd, $fyMonthsInStartYear, $fyMonthsInEndYear) {
                return (in_array($p->month, $fyMonthsInStartYear) && (int)$p->year === (int)$fyStart)
                    || (in_array($p->month, $fyMonthsInEndYear)   && (int)$p->year === (int)$fyEnd);
            });
        }

        // 5. Proses Filter Status
        if ($statusFilter) {
            if ($statusFilter === 'lunas') {
                $allTransactions = $allTransactions->where('payment_status', 'verified');
            } elseif ($statusFilter === 'belum_lunas') {
                $allTransactions = $allTransactions->where('payment_status', 'unpaid');
            } elseif ($statusFilter === 'verifikasi') {
                $allTransactions = $allTransactions->where('payment_status', 'pending');
            } elseif ($statusFilter === 'ditolak') {
                $allTransactions = $allTransactions->where('payment_status', 'rejected');
            }
        }

        // 6. Proses Pencarian Nama Siswa
        if ($search) {
            $searchLower = strtolower($search);
            $allTransactions = $allTransactions->filter(function($payment) use ($searchLower) {
                $studentName = strtolower($payment->student->studentProfile->full_name ?? $payment->student->email ?? '');
                return str_contains($studentName, $searchLower);
            });
        }

        // 7. Urutkan Data (Pending paling atas, lalu berdasar tanggal terbaru)
        $allTransactions = $allTransactions->sort(function($a, $b) {
            if ($a->payment_status === 'pending' && $b->payment_status !== 'pending') return -1;
            if ($a->payment_status !== 'pending' && $b->payment_status === 'pending') return 1;
            return $b->created_at <=> $a->created_at;
        })->values();

        // 8. Manual Pagination & Counts
        $totalCount    = $allTransactions->count();
        $pendingCount  = $allTransactions->where('payment_status', 'pending')->count();
        $verifiedCount = $allTransactions->where('payment_status', 'verified')->count();
        $unpaidCount   = $allTransactions->where('payment_status', 'unpaid')->count();
        $rejectedCount = $allTransactions->where('payment_status', 'rejected')->count();

        $perPage = 10;
        $page    = $request->query('page', 1);
        $paginatedItems = $allTransactions->slice(($page - 1) * $perPage, $perPage);
        $payments = new LengthAwarePaginator(
            $paginatedItems,
            $allTransactions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $monthsList = array_values($monthsName);
        // Gunakan tahun-tahun kalender yang relevan dari data pembayaran yang ada
        $yearsList = AcademicYear::orderBy('year_name')->pluck('year_name')->toArray();

        return view('admin.payments.index', compact(
            'payments',
            'search',
            'statusFilter',
            'monthFilter',
            'yearFilter',
            'monthsList',
            'yearsList',
            'totalCount',
            'pendingCount',
            'verifiedCount',
            'unpaidCount',
            'rejectedCount'
        ));
    }

    /**
     * Menyetujui (verifikasi) bukti pembayaran siswa.
     */
    public function verify($id)
    {
        $payment = Payment::findOrFail($id);
        
        // Ubah status jadi verified dan catat ID admin/guru yang melakukan klik
        $payment->update([
            'payment_status' => 'verified',
            'verified_by' => auth()->id() 
        ]);

        return back()->with('success', 'Pembayaran siswa berhasil diverifikasi dan disetujui.');
    }

    /**
     * Menolak bukti pembayaran siswa (contoh: jika gambar burem atau nominal transfer salah).
     */
    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        
        // Ubah status jadi rejected dan catat siapa yang menolak
        $payment->update([
            'payment_status' => 'rejected',
            'verified_by' => auth()->id()
        ]);

        return back()->with('success', 'Pembayaran siswa telah ditolak.');
    }

    /**
     * Mencetak laporan pembayaran ke PDF berdasarkan filter pencarian
     */
    public function exportPdf(Request $request)
    {
        $statusFilter = $request->query('status');
        $monthFilter = $request->query('month');
        $yearFilter = $request->query('year');
        $search = $request->query('search');

        // Gunakan logika yang sama dengan index() untuk mengambil data
        $realPayments = Payment::with(['student.studentProfile', 'extracurricular', 'verifier'])
            ->get();

        $activeYearPdf = AcademicYear::where('is_active', true)->first();
        $approvedRegistrations = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular', 'academicYear'])
            ->where('status', 'approved')
            ->when($activeYearPdf, fn($q) => $q->where('academic_year_id', $activeYearPdf->id))
            ->get();

        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $unpaidBills = collect();
        $currentDate = now()->startOfMonth();

        foreach ($approvedRegistrations as $reg) {
            if ($reg->extracurricular->fee <= 0) continue;

            // Tentukan rentang tagihan berdasarkan tahun ajaran (Juli – Juni)
            $yearModel = $reg->academicYear ?? $activeYearPdf;
            $yearStart = $yearModel ? $yearModel->startDate() : $reg->created_at->copy()->startOfMonth();
            $yearEnd   = $yearModel ? $yearModel->endDate()->startOfMonth() : $currentDate->copy();

            $billStart    = $reg->created_at->startOfMonth()->max($yearStart);
            $billEnd      = $currentDate->min($yearEnd);
            $iteratorDate = $billStart->copy();

            while ($iteratorDate->lte($billEnd)) {
                $monthIndex = $iteratorDate->month;
                $year       = $iteratorDate->year;
                $monthStr   = $monthsName[$monthIndex];

                $hasPaid = $realPayments->where('student_id', $reg->student_id)
                                    ->where('extracurricular_id', $reg->extracurricular_id)
                                    ->where('month', $monthStr)
                                    ->where('year', $year)
                                    ->whereIn('payment_status', ['pending', 'verified'])
                                    ->first();

                if (!$hasPaid) {
                    $dummyPayment = new Payment([
                        'student_id'         => $reg->student_id,
                        'extracurricular_id' => $reg->extracurricular_id,
                        'month'              => $monthStr,
                        'year'               => $year,
                        'total_amount'       => $reg->extracurricular->fee,
                        'payment_status'     => 'unpaid'
                    ]);
                    $dummyPayment->setRelation('extracurricular', $reg->extracurricular);
                    $dummyPayment->setRelation('student', $reg->student);
                    $dummyPayment->created_at = $iteratorDate->copy();
                    $unpaidBills->push($dummyPayment);
                }
                $iteratorDate->addMonth();
            }
        }

        $allTransactions = $unpaidBills->concat($realPayments);

        if ($monthFilter) {
            $allTransactions = $allTransactions->where('month', $monthFilter);
        }

        if ($yearFilter && str_contains($yearFilter, '/')) {
            [$fyStart, $fyEnd] = explode('/', $yearFilter);
            $fyMonthsInStartYear = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $fyMonthsInEndYear   = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            $allTransactions = $allTransactions->filter(function ($p) use ($fyStart, $fyEnd, $fyMonthsInStartYear, $fyMonthsInEndYear) {
                return (in_array($p->month, $fyMonthsInStartYear) && (int)$p->year === (int)$fyStart)
                    || (in_array($p->month, $fyMonthsInEndYear)   && (int)$p->year === (int)$fyEnd);
            });
        }

        if ($statusFilter) {
            if ($statusFilter === 'lunas') $allTransactions = $allTransactions->where('payment_status', 'verified');
            elseif ($statusFilter === 'belum_lunas') $allTransactions = $allTransactions->where('payment_status', 'unpaid');
            elseif ($statusFilter === 'verifikasi') $allTransactions = $allTransactions->where('payment_status', 'pending');
            elseif ($statusFilter === 'ditolak') $allTransactions = $allTransactions->where('payment_status', 'rejected');
        }

        if ($search) {
            $searchLower = strtolower($search);
            $allTransactions = $allTransactions->filter(function($payment) use ($searchLower) {
                $studentName = strtolower($payment->student->studentProfile->full_name ?? $payment->student->email ?? '');
                return str_contains($studentName, $searchLower);
            });
        }

        $allTransactions = $allTransactions->sort(function($a, $b) {
            if ($a->payment_status === 'pending' && $b->payment_status !== 'pending') return -1;
            if ($a->payment_status !== 'pending' && $b->payment_status === 'pending') return 1;
            return $b->created_at <=> $a->created_at;
        })->values();

        $totalAmount = $allTransactions->where('payment_status', 'verified')->sum('total_amount');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.payments.report-pdf', [
            'payments' => $allTransactions,
            'statusFilter' => $statusFilter,
            'monthFilter' => $monthFilter,
            'yearFilter' => $yearFilter,
            'search' => $search,
            'totalAmount' => $totalAmount,
            'printDate' => now()->translatedFormat('d F Y')
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        
        $filenameMonth = $monthFilter ? '-' . strtolower($monthFilter) : '';
        return $pdf->download('laporan-pembayaran-ekskul' . $filenameMonth . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
