<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PembinaProfile;
use App\Http\Requests\Admin\StorePembinaRequest;
use App\Http\Requests\Admin\UpdatePembinaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembinaController extends Controller
{
    /**
     * Menampilkan daftar data pembina ekstrakurikuler dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::whereIn('role', ['pembina', 'guru'])
            ->with('pembinaProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('pembinaProfile', function($tp) use ($search) {
                      $tp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('teacher_code', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $pembinas = $query->latest()->paginate(10)->appends($request->all());
        $teachers = $pembinas; // Kompatibilitas jika view lama memanggil $teachers

        return view('admin.pembina.index', compact('pembinas', 'teachers', 'search'));
    }

    /**
     * Menampilkan formulir pendaftaran akun dan profil pembina baru.
     */
    public function create()
    {
        return view('admin.pembina.create');
    }

    /**
     * Menyimpan data pembina baru ke dalam database melalui Database Transaction.
     */
    public function store(StorePembinaRequest $request)
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            // 1. Buat akun user pembina
            $user = User::create([
                'email' => $validatedData['email'],
                'role' => 'pembina',
            ]);

            // 2. Buat profil pembina terkait
            PembinaProfile::create([
                'user_id' => $user->id,
                'teacher_code' => $validatedData['pembina_code'] ?? ($validatedData['teacher_code'] ?? null),
                'full_name' => $validatedData['full_name'],
                'nip' => $validatedData['nip'],
                'gender' => $validatedData['gender'],
                'position' => $validatedData['position'],
                'phone_number' => $validatedData['phone_number'],
            ]);
        });

        return redirect()->route('pembina.index')->with('success', 'Data pembina berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir untuk mengubah data pembina.
     */
    public function edit($id)
    {
        $pembina = User::with('pembinaProfile')->findOrFail($id);
        $teacher = $pembina; // Kompatibilitas jika view memanggil $teacher
        return view('admin.pembina.edit', compact('pembina', 'teacher'));
    }

    /**
     * Memperbarui data akun dan profil pembina pada database.
     */
    public function update(UpdatePembinaRequest $request, $id)
    {
        $pembina = User::findOrFail($id);
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $pembina) {
            $pembina->update([
                'email' => $validatedData['email'],
            ]);

            $profile = $pembina->pembinaProfile;
            $profileData = [
                'teacher_code' => $validatedData['pembina_code'] ?? ($validatedData['teacher_code'] ?? null),
                'full_name' => $validatedData['full_name'],
                'nip' => $validatedData['nip'],
                'gender' => $validatedData['gender'],
                'position' => $validatedData['position'],
                'phone_number' => $validatedData['phone_number'],
            ];

            if ($profile) {
                $profile->update($profileData);
            } else {
                $profileData['user_id'] = $pembina->id;
                PembinaProfile::create($profileData);
            }
        });

        return redirect()->route('pembina.index')->with('success', 'Perubahan data pembina berhasil disimpan.');
    }

    /**
     * Menghapus akun dan profil pembina secara permanen.
     */
    public function destroy($id)
    {
        $pembina = User::findOrFail($id);
        
        if ($pembina->pembinaProfile) {
            $pembina->pembinaProfile->delete();
        }
        
        $pembina->delete();

        return redirect()->route('pembina.index')->with('success', 'Data pembina berhasil dihapus permanen.');
    }

    /**
     * Mengunduh berkas template CSV contoh untuk impor data pembina massal.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_data_pembina.csv"',
        ];

        $callback = function () {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            // Header kolom
            fputcsv($output, [
                'Kode Pembina',
                'Nama Lengkap',
                'NIP',
                'Email Belajar.id',
                'Jabatan',
                'Jenis Kelamin',
                'Nomor Telepon',
            ], ';');

            // Baris data contoh
            fputcsv($output, [
                '1',
                'Drs. H. Ahmad Subardjo, M.Pd.',
                '197501012000031001',
                'ahmad.subardjo@guru.smp.belajar.id',
                'Kepala Sekolah',
                'Laki-laki',
                '081234567891',
            ], ';');

            fputcsv($output, [
                '2',
                'Siti Rahmawati, S.Pd.',
                '198203152006042002',
                'siti.rahmawati@guru.smp.belajar.id',
                'Guru',
                'Perempuan',
                '081234567892',
            ], ';');

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Memproses impor massal data pembina dari berkas CSV/Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'Silakan pilih berkas CSV terlebih dahulu.',
            'file.mimes'    => 'Berkas harus berupa file format .csv.',
            'file.max'      => 'Ukuran berkas maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        $firstLine = file_get_contents($filePath, false, null, 0, 1024);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV yang diunggah.');
        }

        fgetcsv($handle, 1000, $delimiter); // header

        $rowNumber = 1;
        $errors = [];
        $pembinasToInsert = [];
        $batchEmails = [];
        $batchNips = [];
        $batchCodes = [];

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;

            if (empty(array_filter($row))) {
                continue;
            }

            if (count($row) < 5) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama, NIP, Email, Jabatan, dan Jenis Kelamin.";
                continue;
            }

            $hasCode = count($row) >= 7 || filter_var($row[3] ?? '', FILTER_VALIDATE_EMAIL);

            if ($hasCode) {
                $pembinaCode = trim(trim($row[0] ?? ''), "=\"' \t\n\r\0\x0B");
                $fullName    = trim($row[1] ?? '');
                $nip         = trim(trim($row[2] ?? ''), "=\"' \t\n\r\0\x0B");
                $email       = trim($row[3] ?? '');
                $positionRaw = strtolower(trim($row[4] ?? ''));
                $genderRaw   = strtolower(trim($row[5] ?? ''));
                $phoneNumber = trim(trim($row[6] ?? ''), "=\"' \t\n\r\0\x0B");
            } else {
                $pembinaCode = null;
                $fullName    = trim($row[0] ?? '');
                $nip         = trim(trim($row[1] ?? ''), "=\"' \t\n\r\0\x0B");
                $email       = trim($row[2] ?? '');
                $positionRaw = strtolower(trim($row[3] ?? ''));
                $genderRaw   = strtolower(trim($row[4] ?? ''));
                $phoneNumber = trim(trim($row[5] ?? ''), "=\"' \t\n\r\0\x0B");
            }

            if ($pembinaCode !== null && $pembinaCode !== '') {
                if (strlen($pembinaCode) > 10) {
                    $errors[] = "Baris ke-{$rowNumber}: Kode pembina '{$pembinaCode}' maksimal 10 karakter.";
                    continue;
                }
                if (in_array($pembinaCode, $batchCodes)) {
                    $errors[] = "Baris ke-{$rowNumber}: Kode pembina '{$pembinaCode}' terdeteksi ganda di dalam berkas yang sama.";
                    continue;
                }
                $batchCodes[] = $pembinaCode;
            }

            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            if (empty($nip) || strlen($nip) > 30) {
                $errors[] = "Baris ke-{$rowNumber}: NIP '{$nip}' wajib diisi dan maksimal 30 karakter.";
                continue;
            }

            if (in_array($nip, $batchNips)) {
                $errors[] = "Baris ke-{$rowNumber}: NIP '{$nip}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris ke-{$rowNumber}: Format email '{$email}' tidak valid.";
                continue;
            }

            if (in_array($email, $batchEmails)) {
                $errors[] = "Baris ke-{$rowNumber}: Email '{$email}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            if (in_array($positionRaw, ['kepala sekolah', 'kepala_sekolah', 'kepsek'])) {
                $position = 'kepala_sekolah';
            } elseif (in_array($positionRaw, ['wakil kepala sekolah', 'wakil_kepala_sekolah', 'wakasek', 'waka'])) {
                $position = 'wakil_kepala_sekolah';
            } elseif (in_array($positionRaw, ['pembina', 'guru', 'pengajar', 'guru pengajar'])) {
                $position = 'guru';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jabatan '{$positionRaw}' tidak valid. Gunakan 'Pembina', 'Kepala Sekolah', atau 'Wakil Kepala Sekolah'.";
                continue;
            }

            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$genderRaw}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            $batchEmails[] = $email;
            $batchNips[]   = $nip;

            $pembinasToInsert[] = [
                'teacher_code' => $pembinaCode ?: null,
                'full_name'    => $fullName,
                'nip'          => $nip,
                'email'        => $email,
                'position'     => $position,
                'gender'       => $gender,
                'phone_number' => $phoneNumber ?: null,
            ];
        }

        fclose($handle);

        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->with('error', 'Proses impor dibatalkan karena ditemukan kesalahan pada format data. Silakan periksa rincian kesalahan di bawah ini.');
        }

        if (empty($pembinasToInsert)) {
            return redirect()->back()->with('error', 'Berkas CSV tidak memuat data pembina yang dapat diimpor.');
        }

        DB::transaction(function () use ($pembinasToInsert) {
            foreach ($pembinasToInsert as $pembinaData) {
                $user = User::firstOrCreate(
                    ['email' => $pembinaData['email']],
                    ['role'  => 'pembina']
                );

                PembinaProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'teacher_code' => $pembinaData['teacher_code'],
                        'full_name'    => $pembinaData['full_name'],
                        'nip'          => $pembinaData['nip'],
                        'gender'       => $pembinaData['gender'],
                        'position'     => $pembinaData['position'],
                        'phone_number' => $pembinaData['phone_number'],
                    ]
                );
            }
        });

        $total = count($pembinasToInsert);
        return redirect()->route('pembina.index')->with('success', "Alhamdulillah! Sebanyak {$total} data pembina berhasil diimpor ke sistem.");
    }
}
