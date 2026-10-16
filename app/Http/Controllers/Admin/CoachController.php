<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoachProfile;
use App\Http\Requests\Admin\StoreCoachRequest;
use App\Http\Requests\Admin\UpdateCoachRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CoachController extends Controller
{
    /**
     * Menampilkan daftar data coach / pembina ekstrakurikuler dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'coach')
            ->with('coachProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('coachProfile', function($cp) use ($search) {
                      $cp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $coaches = $query->orderBy(CoachProfile::select('full_name')->whereColumn('coach_profiles.user_id', 'users.id'), 'asc')
            ->paginate(10)
            ->appends($request->all());

        return view('admin.coaches.index', compact('coaches', 'search'));
    }

    /**
     * Menampilkan formulir pendaftaran akun dan profil coach baru.
     */
    public function create()
    {
        return view('admin.coaches.create');
    }

    /**
     * Menyimpan data coach baru ke dalam database melalui Database Transaction.
     */
    public function store(StoreCoachRequest $request)
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            // 1. Buat akun user coach dengan password default 'pembinasmpsmi'
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => 'pembinasmpsmi',
                'must_change_password' => true,
                'role' => 'coach',
            ]);

            // 2. Buat profil coach terkait
            CoachProfile::create([
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'gender' => $validatedData['gender'],
                'phone_number' => $validatedData['phone_number'],
            ]);
        });

        return redirect()->route('coaches.index')->with('success', 'Data pembina / coach berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir untuk mengubah data coach.
     */
    public function edit($id)
    {
        $coach = User::with('coachProfile')->findOrFail($id);
        return view('admin.coaches.edit', compact('coach'));
    }

    /**
     * Memperbarui data akun dan profil coach pada database.
     */
    public function update(UpdateCoachRequest $request, $id)
    {
        $coach = User::findOrFail($id);
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $coach) {
            $coach->update([
                'username' => $validatedData['username'],
            ]);

            $profile = $coach->coachProfile;
            $profileData = [
                'full_name' => $validatedData['full_name'],
                'gender' => $validatedData['gender'],
                'phone_number' => $validatedData['phone_number'],
            ];

            if ($profile) {
                $profile->update($profileData);
            } else {
                $profileData['user_id'] = $coach->id;
                CoachProfile::create($profileData);
            }
        });

        return redirect()->route('coaches.index')->with('success', 'Perubahan data pembina / coach berhasil disimpan.');
    }

    /**
     * Menghapus akun dan profil coach secara permanen.
     */
    public function destroy($id)
    {
        $coach = User::findOrFail($id);
        
        if ($coach->coachProfile) {
            $coach->coachProfile->delete();
        }
        
        $coach->delete();

        return redirect()->route('coaches.index')->with('success', 'Data pembina / coach berhasil dihapus permanen.');
    }

    /**
     * Me-reset kata sandi akun pembina ke kata sandi default sistem ('pembinasmpsmi')
     * dan mewajibkan pembina untuk mengganti kata sandi saat login berikutnya.
     */
    public function resetPassword($id)
    {
        $coach = User::where('role', 'coach')->with('coachProfile')->findOrFail($id);

        $coach->update([
            'password'             => 'pembinasmpsmi',
            'must_change_password' => true,
        ]);

        $name = $coach->coachProfile->full_name ?? $coach->username;

        return redirect()->back()->with('success', "Kata sandi pembina {$name} berhasil di-reset ke default ('pembinasmpsmi'). Pembina diwajibkan membuat kata sandi baru saat login kembali.");
    }

    /**
     * Mengunduh berkas template Excel (.xlsx) untuk impor data coach / pembina massal.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pembina');

        // Header Kolom
        $headers = [
            'A1' => 'Nama Lengkap & Gelar',
            'B1' => 'Username Pembina',
            'C1' => 'Jenis Kelamin',
            'D1' => 'No. Telepon',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Contoh Data
        $sampleData = [
            ['Drs. H. Ahmad Subardjo, M.Pd.', 'ahmadsubardjo', 'Laki-laki', '081234567891'],
            ['Siti Rahmawati, S.Pd.', 'sitirahmawati', 'Perempuan', '081234567892'],
        ];

        $rowIdx = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValue('A' . $rowIdx, $row[0]);
            $sheet->setCellValue('B' . $rowIdx, $row[1]);
            $sheet->setCellValue('C' . $rowIdx, $row[2]);
            $sheet->setCellValueExplicit('D' . $rowIdx, $row[3], DataType::TYPE_STRING);
            $rowIdx++;
        }

        // Format kolom No. Telepon sebagai Text (@) agar angka 0 di depan tidak hilang
        $sheet->getStyle('D2:D500')->getNumberFormat()->setFormatCode('@');

        // Styling Header (Warna Navy, Font Putih Tebal, Center)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Lebar kolom otomatis menyesuaikan panjang teks
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_impor_pembina.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Memproses impor massal data coach dari berkas Excel (.xlsx / .xls).
     */
    public function import(Request $request)
    {
        // 1. Validasi berkas unggahan: harus berupa berkas Excel dengan ukuran maksimal 10MB
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Silakan pilih berkas Excel (.xlsx / .xls) terlebih dahulu.',
            'file.mimes'    => 'Berkas harus berupa file format Excel (.xlsx atau .xls).',
            'file.max'      => 'Ukuran berkas maksimal adalah 10MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // 2. Membaca isi spreadsheet menggunakan PhpSpreadsheet
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, false, false);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca berkas Excel: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return redirect()->back()->with('error', 'Berkas Excel tidak memuat data pembina (hanya baris header atau kosong).');
        }

        // Lewati baris header pertama
        array_shift($rows);

        $rowNumber = 1;
        $errors = [];
        $coachesToInsert = [];
        $batchEmails = [];

        // 3. Membaca dan memvalidasi setiap baris data dari berkas Excel
        foreach ($rows as $row) {
            $rowNumber++;

            // Abaikan baris kosong
            if (empty(array_filter($row, fn($val) => $val !== null && trim((string)$val) !== ''))) {
                continue;
            }

            // Pastikan baris memiliki minimal 3 kolom utama
            if (count($row) < 3) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama Lengkap, Username, dan Jenis Kelamin.";
                continue;
            }

            $fullName    = trim((string)($row[0] ?? ''));
            $usernameRaw = trim((string)($row[1] ?? ''));
            $genderRaw   = strtolower(trim((string)($row[2] ?? '')));
            $phoneNumber = trim(trim((string)($row[3] ?? '')), "=\"' \t\n\r\0\x0B");

            // Format username: jika user menulis email (misal: ahmad@smpsmi.com), ambil username depannya
            $username = str_contains($usernameRaw, '@') ? explode('@', $usernameRaw)[0] : $usernameRaw;
            $username = strtolower(trim($username));

            // Validasi Nama Lengkap
            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            // Validasi Username
            if (empty($username) || strlen($username) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Username pembina '{$usernameRaw}' tidak valid (maksimal 100 karakter).";
                continue;
            }

            // Validasi duplikasi di file yang sama
            if (in_array($username, $batchEmails)) {
                $errors[] = "Baris ke-{$rowNumber}: Username '{$usernameRaw}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Cek apakah username digunakan oleh role lain
            $existingNonCoach = User::where('username', $username)->where('role', '!=', 'coach')->first();
            if ($existingNonCoach) {
                $errors[] = "Baris ke-{$rowNumber}: Username '{$username}' sudah terdaftar untuk pengguna dengan peran {$existingNonCoach->role}.";
                continue;
            }

            // Normalisasi Jenis Kelamin
            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria', 'laki'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$genderRaw}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            $batchEmails[] = $username;

            $coachesToInsert[] = [
                'full_name'    => $fullName,
                'username'     => $username,
                'gender'       => $gender,
                'phone_number' => $phoneNumber ?: null,
            ];
        }

        // Jika terdapat baris yang bermasalah, kembalikan dengan pesan kesalahan lengkap
        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->with('error', 'Proses impor dibatalkan karena ditemukan kesalahan pada format data. Silakan periksa rincian kesalahan di bawah ini.');
        }

        // Pastikan ada setidaknya satu baris data valid untuk diimpor
        if (empty($coachesToInsert)) {
            return redirect()->back()->with('error', 'Berkas Excel tidak memuat data pembina yang dapat diimpor.');
        }

        // 4. Eksekusi penyimpanan ke basis data menggunakan Database Transaction
        DB::transaction(function () use ($coachesToInsert) {
            foreach ($coachesToInsert as $coachData) {
                $user = User::firstOrCreate(
                    ['username' => $coachData['username']],
                    [
                        'password'             => 'pembinasmpsmi',
                        'must_change_password' => true,
                        'role'                 => 'coach',
                    ]
                );

                CoachProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name'    => $coachData['full_name'],
                        'gender'       => $coachData['gender'],
                        'phone_number' => $coachData['phone_number'],
                    ]
                );
            }
        });

        $total = count($coachesToInsert);
        return redirect()->route('coaches.index')->with('success', "Alhamdulillah! Sebanyak {$total} data pembina berhasil diimpor ke sistem.");
    }
}
