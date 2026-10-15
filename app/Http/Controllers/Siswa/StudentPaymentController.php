<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentPaymentController extends Controller
{
    /**
     * Menampilkan riwayat pembayaran siswa.
     * Mengambil daftar pembayaran yang pernah dilakukan oleh siswa tersebut,
     * sekaligus daftar ekskul yang statusnya sudah 'approved' untuk dimasukkan ke form opsi bayar.
     */
    public function index()
    {
        // Ambil riwayat pembayaran khusus siswa yang login (berdasarkan id)
        $payments = Payment::with(['extracurricular', 'verifier'])
            ->where('student_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Ambil daftar ekskul yang disetujui untuk ditampilkan pada opsi pilihan form "Bayar"
        $approvedRegistrations = ExtracurricularRegistration::with(['extracurricular', 'academicYear'])
            ->where('student_id', auth()->id())
            ->where('status', 'approved')
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->get();

        // LOGIKA TAGIHAN (BELUM LUNAS)
        // Tagihan dihitung per bulan (tiap tanggal 1) dalam rentang:
        //   Mulai : yang lebih akhir antara tanggal daftar siswa ATAU awal tahun ajaran (1 Juli)
        //   Selesai: yang lebih awal antara bulan ini ATAU akhir tahun ajaran (30 Juni)
        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $unpaidBills = collect();
        $currentDate = now()->startOfMonth();

        foreach ($approvedRegistrations as $reg) {
            // Abaikan jika ekskul tersebut gratis (fee 0)
            if ($reg->extracurricular->fee <= 0) {
                continue;
            }

            // Tentukan rentang tagihan berdasarkan tahun ajaran (Juli – Juni)
            $yearModel   = $reg->academicYear ?? $activeYear;
            $yearStart   = $yearModel ? $yearModel->startDate() : $reg->created_at->copy()->startOfMonth();
            $yearEnd     = $yearModel ? $yearModel->endDate()->startOfMonth() : $currentDate->copy();

            // Tagihan mulai dari yang lebih akhir: tanggal daftar vs awal tahun ajaran
            $billStart   = $reg->created_at->startOfMonth()->max($yearStart);
            // Tagihan berakhir di yang lebih awal: bulan ini vs akhir tahun ajaran
            $billEnd     = $currentDate->min($yearEnd);

            $iteratorDate = $billStart->copy();

            while ($iteratorDate->lte($billEnd)) {
                $monthIndex = $iteratorDate->month;
                $year       = $iteratorDate->year;
                $monthStr   = $monthsName[$monthIndex];

                // Cek apakah ekskul ini sudah dibayar untuk bulan dan tahun tersebut (pending atau verified)
                $hasPaid = $payments->where('extracurricular_id', $reg->extracurricular_id)
                                    ->where('month', $monthStr)
                                    ->where('year', $year)
                                    ->whereIn('payment_status', ['pending', 'verified'])
                                    ->first();

                if (!$hasPaid) {
                    // Buat objek dummy payment berstatus "unpaid"
                    $dummyPayment = new Payment([
                        'extracurricular_id' => $reg->extracurricular_id,
                        'month'              => $monthStr,
                        'year'               => $year,
                        'total_amount'       => $reg->extracurricular->fee,
                        'payment_status'     => 'unpaid'
                    ]);

                    // Set relasi ekskul secara manual agar view tidak error saat memanggil nama ekskul
                    $dummyPayment->setRelation('extracurricular', $reg->extracurricular);
                    $dummyPayment->created_at = $iteratorDate->copy();

                    $unpaidBills->push($dummyPayment);
                }

                // Lanjut ke bulan berikutnya
                $iteratorDate->addMonth();
            }
        }

        // Gabungkan tagihan virtual (belum lunas) dengan riwayat pembayaran asli, lalu urutkan
        $sortedTransactions = $unpaidBills->concat($payments)->sortByDesc('created_at')->values();

        // Paginasi Manual (10 data per halaman)
        $perPage = 10;
        $page = request()->query('page', 1);
        $paginatedItems = $sortedTransactions->slice(($page - 1) * $perPage, $perPage);
        $allTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems, 
            $sortedTransactions->count(), 
            $perPage, 
            $page, 
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('siswa.payments.index', compact('allTransactions', 'approvedRegistrations'));
    }

    /**
     * Memproses form upload bukti pembayaran bulanan.
     */
    public function store(Request $request)
    {
        // Validasi input form: jika metode transfer, bukti bayar (foto) wajib diunggah.
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'payment_method' => 'required|in:transfer,cash',
            'proof_of_payment' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Validasi 1: Cegah pembayaran untuk bulan di masa depan
        $monthsMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];
        
        $selectedMonthIndex = $monthsMap[$request->month] ?? 0;
        $selectedYear = (int) $request->year;

        $currentMonthIndex = (int) date('n');
        $currentYear = (int) date('Y');

        if ($selectedYear > $currentYear || ($selectedYear === $currentYear && $selectedMonthIndex > $currentMonthIndex)) {
            return back()->withErrors(['Bulan pembayaran tidak boleh melebihi bulan saat ini.']);
        }

        // Validasi 2: Cegah duplikasi pembayaran (jika sudah ada riwayat pending atau verified)
        $existingPayment = Payment::where('student_id', auth()->id())
            ->where('extracurricular_id', $request->extracurricular_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->whereIn('payment_status', ['pending', 'verified'])
            ->first();

        if ($existingPayment) {
            return back()->withErrors(['Anda sudah memiliki riwayat pembayaran untuk bulan tersebut yang sedang diproses atau telah diverifikasi.']);
        }

        // Cek apakah siswa benar-benar anggota aktif ekskul tersebut di tahun ajaran aktif
        $storeActiveYear = AcademicYear::where('is_active', true)->first();
        $registration = ExtracurricularRegistration::with(['extracurricular', 'academicYear'])
            ->where('student_id', auth()->id())
            ->where('extracurricular_id', $request->extracurricular_id)
            ->where('status', 'approved')
            ->when($storeActiveYear, fn($q) => $q->where('academic_year_id', $storeActiveYear->id))
            ->firstOrFail();

        // Validasi 3: Bulan yang dibayar harus masuk dalam rentang tagihan yang nyata
        // Rentang: MAX(tanggal daftar siswa, awal tahun ajaran) → MIN(bulan ini, akhir tahun ajaran)
        $yearModel  = $registration->academicYear ?? $storeActiveYear;
        $yearStart  = $yearModel ? $yearModel->startDate() : $registration->created_at->copy()->startOfMonth();
        $yearEnd    = $yearModel ? $yearModel->endDate()->startOfMonth() : now()->startOfMonth();

        $billStart  = $registration->created_at->startOfMonth()->max($yearStart);
        $billEnd    = now()->startOfMonth()->min($yearEnd);

        // Konversi bulan/tahun yang dikirim ke Carbon untuk dibandingkan
        $selectedDate = \Carbon\Carbon::create($selectedYear, $selectedMonthIndex, 1)->startOfMonth();

        if ($selectedDate->lt($billStart)) {
            return back()->withErrors([
                'Bulan ' . $request->month . ' ' . $selectedYear . ' bukan termasuk tagihan Anda. Tagihan dimulai dari ' .
                $billStart->translatedFormat('F Y') . '.'
            ]);
        }

        if ($selectedDate->gt($billEnd)) {
            return back()->withErrors([
                'Bulan ' . $request->month . ' ' . $selectedYear . ' belum termasuk tagihan Anda.'
            ]);
        }

        $proofPath = null;

        // Jika ada file bukti transfer yang diunggah, simpan ke direktori storage/app/public/payments
        if ($request->hasFile('proof_of_payment')) {
            $proofPath = $request->file('proof_of_payment')->store('payments', 'public');
        }

        // Masukkan data pembayaran ke tabel 'payments', default status = 'pending'
        Payment::create([
            'student_id' => auth()->id(),
            'extracurricular_id' => $request->extracurricular_id,
            'month' => $request->month,
            'year' => $request->year,
            'total_amount' => $registration->extracurricular->fee,
            'payment_method' => $request->payment_method,
            'proof_of_payment' => $proofPath,
            'payment_status' => 'pending'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Harap tunggu proses verifikasi oleh Admin.');
    }
}
