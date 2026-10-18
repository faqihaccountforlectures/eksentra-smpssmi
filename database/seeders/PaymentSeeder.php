<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ExtracurricularRegistration;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data transaksi pembayaran iuran eskul:
     * - TA 2025/2026: Lunas semua (Juli 2025 - Juni 2026), status verified.
     * - TA 2026/2027: Berjalan sampai Oktober 2026 (Juli-Sep lunas, Oktober campur verified & pending).
     * - Tanggal bayar: tanggal 1 - 5 setiap bulannya.
     * - Metode pembayaran: kombinasi transfer bank dan tunai (cash).
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $year2025 = AcademicYear::where('year_name', '2025/2026')->first();
        $year2026 = AcademicYear::where('year_name', '2026/2027')->first();

        // Cari file bukti transfer yang ada di storage/app/public/payments
        $existingProofFiles = [];
        $proofDir = storage_path('app/public/payments');
        if (File::isDirectory($proofDir)) {
            $files = File::files($proofDir);
            foreach ($files as $file) {
                $existingProofFiles[] = 'payments/' . $file->getFilename();
            }
        }

        // ========================================================
        // 1. PEMBAYARAN TA 2025/2026 (12 Bulan Lunas Seluruhnya)
        // ========================================================
        if ($year2025) {
            $registrations2025 = ExtracurricularRegistration::with('extracurricular')
                ->where('academic_year_id', $year2025->id)
                ->get();

            $months2025 = [
                ['name' => 'Juli', 'm' => 7, 'y' => 2025],
                ['name' => 'Agustus', 'm' => 8, 'y' => 2025],
                ['name' => 'September', 'm' => 9, 'y' => 2025],
                ['name' => 'Oktober', 'm' => 10, 'y' => 2025],
                ['name' => 'November', 'm' => 11, 'y' => 2025],
                ['name' => 'Desember', 'm' => 12, 'y' => 2025],
                ['name' => 'Januari', 'm' => 1, 'y' => 2026],
                ['name' => 'Februari', 'm' => 2, 'y' => 2026],
                ['name' => 'Maret', 'm' => 3, 'y' => 2026],
                ['name' => 'April', 'm' => 4, 'y' => 2026],
                ['name' => 'Mei', 'm' => 5, 'y' => 2026],
                ['name' => 'Juni', 'm' => 6, 'y' => 2026],
            ];

            foreach ($registrations2025 as $regIdx => $reg) {
                $fee = $reg->extracurricular ? $reg->extracurricular->fee : 100000;

                foreach ($months2025 as $mIdx => $m) {
                    // Penentuan tanggal bayar: tanggal 1 sampai 5
                    $day = 1 + (($regIdx + $mIdx) % 5);
                    $hour = 8 + (($regIdx + $mIdx) % 8);
                    $minute = ($regIdx * 7) % 60;
                    $paymentDate = Carbon::create($m['y'], $m['m'], $day, $hour, $minute, 0);

                    // Metode: bergantian antara transfer dan cash (misal 60% transfer, 40% cash)
                    $isTransfer = (($regIdx + $mIdx) % 5 !== 0);
                    $method = $isTransfer ? 'transfer' : 'cash';

                    $proof = null;
                    if ($isTransfer && !empty($existingProofFiles)) {
                        $proof = $existingProofFiles[($regIdx + $mIdx) % count($existingProofFiles)];
                    }

                    Payment::updateOrCreate(
                        [
                            'registration_id' => $reg->id,
                            'month' => $m['name'],
                            'year' => $m['y'],
                        ],
                        [
                            'total_amount' => $fee,
                            'payment_method' => $method,
                            'proof_of_payment' => $proof,
                            'payment_status' => 'verified',
                            'verified_by' => $adminId,
                            'created_at' => $paymentDate,
                            'updated_at' => $paymentDate->copy()->addHours(2),
                        ]
                    );
                }
            }
        }

        // ========================================================
        // 2. PEMBAYARAN TA 2026/2027 (Juli - Oktober 2026)
        // ========================================================
        if ($year2026) {
            $registrations2026 = ExtracurricularRegistration::with('extracurricular')
                ->where('academic_year_id', $year2026->id)
                ->get();

            $months2026 = [
                ['name' => 'Juli', 'm' => 7, 'y' => 2026, 'all_verified' => true],
                ['name' => 'Agustus', 'm' => 8, 'y' => 2026, 'all_verified' => true],
                ['name' => 'September', 'm' => 9, 'y' => 2026, 'all_verified' => true],
                ['name' => 'Oktober', 'm' => 10, 'y' => 2026, 'all_verified' => false],
            ];

            foreach ($registrations2026 as $regIdx => $reg) {
                $fee = $reg->extracurricular ? $reg->extracurricular->fee : 100000;

                foreach ($months2026 as $mIdx => $m) {
                    // Untuk bulan Oktober 2026 (bulan berjalan):
                    // - 60% siswa sudah verified
                    // - 25% siswa baru upload (pending)
                    // - 15% siswa belum bayar sama sekali (tidak di-create pembayarannya)
                    if (!$m['all_verified']) {
                        $mod = ($regIdx % 10);
                        if ($mod >= 8) {
                            // 15-20% belum bayar, lewati agar tampil sebagai "Belum Lunas"
                            continue;
                        }
                        $status = ($mod >= 5) ? 'pending' : 'verified';
                    } else {
                        $status = 'verified';
                    }

                    $day = 1 + (($regIdx + $mIdx) % 5);
                    $hour = 8 + (($regIdx + $mIdx) % 8);
                    $minute = ($regIdx * 11) % 60;
                    $paymentDate = Carbon::create($m['y'], $m['m'], $day, $hour, $minute, 0);

                    $isTransfer = (($regIdx + $mIdx) % 4 !== 0);
                    $method = $isTransfer ? 'transfer' : 'cash';

                    $proof = null;
                    if ($isTransfer && !empty($existingProofFiles)) {
                        $proof = $existingProofFiles[($regIdx + $mIdx) % count($existingProofFiles)];
                    }

                    Payment::updateOrCreate(
                        [
                            'registration_id' => $reg->id,
                            'month' => $m['name'],
                            'year' => $m['y'],
                        ],
                        [
                            'total_amount' => $fee,
                            'payment_method' => $method,
                            'proof_of_payment' => $proof,
                            'payment_status' => $status,
                            'verified_by' => ($status === 'verified') ? $adminId : null,
                            'created_at' => $paymentDate,
                            'updated_at' => ($status === 'verified') ? $paymentDate->copy()->addHours(3) : $paymentDate,
                        ]
                    );
                }
            }
        }
    }
}
