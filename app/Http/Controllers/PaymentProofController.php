<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PaymentProofController extends Controller
{
    /**
     * Menampilkan file bukti pembayaran secara aman dengan otorisasi ketat.
     * Hanya Admin dan Siswa pemilik pembayaran yang diizinkan melihat dokumen bukti transfer ini.
     *
     * @param int $id ID Pembayaran
     * @return Response
     */
    public function show($id)
    {
        $payment = Payment::with('registration')->findOrFail($id);
        $user = Auth::user();

        // 1. Verifikasi Hak Akses (Otorisasi)
        $isAllowed = false;

        if ($user->role === 'admin') {
            // Admin berhak melihat seluruh bukti transfer untuk keperluan verifikasi
            $isAllowed = true;
        } elseif ($user->role === 'siswa' && $payment->registration && $payment->registration->student_id === $user->id) {
            // Siswa hanya boleh melihat bukti transfer miliknya sendiri
            $isAllowed = true;
        }

        if (!$isAllowed) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat bukti pembayaran ini.');
        }

        if (!$payment->proof_of_payment) {
            abort(404, 'Bukti pembayaran tidak ditemukan untuk transaksi ini.');
        }

        // 2. Pencarian Berkas Fisik: Prioritaskan disk privat (local), fallback ke public jika data lama
        $filePath = $payment->proof_of_payment;
        $disk = 'local';

        if (!Storage::disk('local')->exists($filePath)) {
            if (Storage::disk('public')->exists($filePath)) {
                $disk = 'public';
            } else {
                abort(404, 'Berkas fisik bukti transfer tidak ditemukan pada penyimpanan server.');
            }
        }

        return Storage::disk($disk)->response($filePath);
    }
}
