<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;
use App\Models\AcademicYear;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StudentController extends Controller
{
    // Nampilin daftar siswa di tabel utama dengan pencarian & paginasi
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::where('role', 'siswa')
            ->with('studentProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($sp) use ($search) {
                      $sp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('parent_phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->whereHas('studentProfile', function($sp) use ($status) {
                $sp->where('status', $status);
            });
        }

        $students = $query->orderBy(StudentProfile::select('full_name')->whereColumn('student_profiles.user_id', 'users.id'), 'asc')
            ->paginate(10)
            ->appends($request->all());

        return view('admin.students.index', compact('students', 'search', 'status'));
    }

    // Nampilin form tambah data
    public function create()
    {
        return view('admin.students.create');
    }

    // Proses simpan data ke 2 tabel pakai transaction
    public function store(StoreStudentRequest $request)
    {
        // 1. Data sudah tervalidasi oleh StoreStudentRequest
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            // Akun login siswa menggunakan username berupa NIS dengan password default 'siswasmpsmi'
            $user = User::create([
                'username' => $validatedData['nis'],
                'password' => 'siswasmpsmi',
                'must_change_password' => true,
                'role' => 'siswa',
            ]);

            // Bikin profil siswanya
            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'nis' => $validatedData['nis'],
                'gender' => $validatedData['gender'],
                'phone_number' => $validatedData['phone_number'],
                'parent_phone' => $validatedData['parent_phone'],
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Sip! Data siswa baru berhasil ditambahkan.');
    }

    // Nampilin form edit
    public function edit($id)
    {
        $student = User::with('studentProfile')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // Proses update data
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = User::findOrFail($id);

        // 1. Ambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $student) {
            // Update username sistem sesuai NIS siswa
            $student->update([
                'username' => $validatedData['nis'],
            ]);

            // Update profil siswanya
            if ($student->studentProfile) {
                $student->studentProfile->update([
                    'full_name' => $validatedData['full_name'],
                    'nis' => $validatedData['nis'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            } else {
                StudentProfile::create([
                    'user_id' => $student->id,
                    'full_name' => $validatedData['full_name'],
                    'nis' => $validatedData['nis'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            }
        });

        return redirect()->route('students.index')->with('success', 'Oke mantap! Perubahan data siswa sudah tersimpan.');
    }

    // Proses hapus data
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Hapus profilnya dulu biar bersih, baru hapus akunnya
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }
        
        $student->delete();

        return redirect()->back()->with('success', 'Data siswa tersebut berhasil dihapus permanen.');
    }

    /**
     * Me-reset kata sandi akun siswa ke kata sandi default sistem ('siswasmpsmi')
     * dan mewajibkan siswa untuk mengganti kata sandi saat login berikutnya.
     */
    public function resetPassword($id)
    {
        $student = User::where('role', 'siswa')->with('studentProfile')->findOrFail($id);

        $student->update([
            'password'             => 'siswasmpsmi',
            'must_change_password' => true,
        ]);

        $name = $student->studentProfile->full_name ?? $student->username;

        return redirect()->back()->with('success', "Kata sandi siswa {$name} berhasil di-reset ke default ('siswasmpsmi'). Siswa diwajibkan membuat kata sandi baru saat login kembali.");
    }

    /**
     * Mengunduh berkas template Excel (.xlsx) untuk impor data siswa massal.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Header Kolom
        $headers = [
            'A1' => 'Nama Lengkap',
            'B1' => 'NIS',
            'C1' => 'Jenis Kelamin',
            'D1' => 'Nomor HP Siswa',
            'E1' => 'Nomor HP Orang Tua',
            'F1' => 'Kelas (Opsional)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Contoh Data
        $sampleData = [
            ['Ahmad Fauzi', '12345678', 'Laki-laki', '081234567890', '081298765432', '7A'],
            ['Siti Aisyah', '12345679', 'Perempuan', '082134567891', '082198765433', '7B'],
        ];

        $rowIdx = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValue('A' . $rowIdx, $row[0]);
            $sheet->setCellValueExplicit('B' . $rowIdx, $row[1], DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowIdx, $row[2]);
            $sheet->setCellValueExplicit('D' . $rowIdx, $row[3], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $rowIdx, $row[4], DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $rowIdx, $row[5]);
            $rowIdx++;
        }

        // Format kolom NIS dan Nomor HP sebagai Text (@) agar angka tidak berubah jadi ilmiah di Excel
        $sheet->getStyle('B2:B500')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('D2:D500')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('E2:E500')->getNumberFormat()->setFormatCode('@');

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
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Lebar kolom otomatis menyesuaikan panjang konten
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_impor_siswa.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Memproses impor massal data siswa dari berkas CSV/Excel.
     * Sistem memvalidasi setiap baris data, membuat akun user, profil siswa,
     * serta langsung mendaftarkan siswa ke kelas rombel jika kolom Kelas diisi.
     */
    public function import(Request $request)
    {
        // 1. Validasi berkas unggahan: harus berupa berkas Excel dengan ukuran maksimal 10MB
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'file.required' => 'Silakan pilih berkas Excel terlebih dahulu.',
            'file.mimes'    => 'Format berkas harus berupa Excel (.xlsx atau .xls).',
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
            return redirect()->back()->with('error', 'Berkas Excel tidak memuat data siswa (hanya baris header atau kosong).');
        }

        // Lewati baris header pertama
        $header = array_shift($rows);

        // Ambil tahun ajaran yang sedang aktif dan buat peta pencarian kelas cerdas
        $activeYear = AcademicYear::where('is_active', true)->first();
        $allClasses = ClassRoom::all();

        // Peta kelas: Mendukung pencocokan format fleksibel (7A, VII A, 8B, VIII B, 9A, IX A, dsb.)
        $classMap = [];
        foreach ($allClasses as $cls) {
            $raw = strtolower(trim($cls->name));
            $clean = preg_replace('/\s+|-/', '', $raw);

            $classMap[$raw] = $cls->id;
            $classMap[$clean] = $cls->id;

            // Pemetaan variasi Romawi ke Angka Arab
            $toArabic = str_replace(['viii', 'vii', 'ix', 'vi', 'x'], ['8', '7', '9', '6', '10'], $clean);
            $classMap[$toArabic] = $cls->id;

            // Pemetaan variasi Angka Arab ke Romawi
            $toRoman = str_replace(['8', '7', '9', '6', '10'], ['viii', 'vii', 'ix', 'vi', 'x'], $clean);
            $classMap[$toRoman] = $cls->id;
        }

        $rowNumber = 1;
        $errors = [];
        $studentsToInsert = [];
        $batchEmails = [];
        $batchNis = [];

        // 3. Membaca dan memvalidasi setiap baris data dari berkas Excel
        foreach ($rows as $row) {
            $rowNumber++;

            // Abaikan baris yang seluruh kolomnya kosong
            if (empty(array_filter($row, fn($val) => $val !== null && trim((string)$val) !== ''))) {
                continue;
            }

            // Pastikan baris memiliki minimal 3 kolom utama (Nama, NIS, dan Jenis Kelamin)
            if (count($row) < 3) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama, NIS, dan Jenis Kelamin.";
                continue;
            }

            // Bersihkan data dari karakter ekstra tanda kutip / formula Excel (contoh: ="0812..." atau '0812...)
            $fullName    = trim($row[0] ?? '');
            $nis         = trim(trim($row[1] ?? ''), "=\"' \t\n\r\0\x0B");

            // Deteksi format: apakah kolom ke-2 adalah username/email lama atau langsung jenis kelamin
            $col2 = trim($row[2] ?? '');
            $hasOldEmailColumn = str_contains($col2, '@') && filter_var($col2, FILTER_VALIDATE_EMAIL);

            if ($hasOldEmailColumn) {
                $genderRaw   = strtolower(trim($row[3] ?? ''));
                $phoneNumber = trim(trim($row[4] ?? ''), "=\"' \t\n\r\0\x0B");
                $parentPhone = trim(trim($row[5] ?? ''), "=\"' \t\n\r\0\x0B");
                $className   = trim(trim($row[6] ?? ''), "=\"' \t\n\r\0\x0B");
            } else {
                $genderRaw   = strtolower($col2);
                $phoneNumber = trim(trim($row[3] ?? ''), "=\"' \t\n\r\0\x0B");
                $parentPhone = trim(trim($row[4] ?? ''), "=\"' \t\n\r\0\x0B");
                $className   = trim(trim($row[5] ?? ''), "=\"' \t\n\r\0\x0B");
            }

            // Validasi Nama Lengkap
            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            // Validasi NIS
            if (empty($nis) || strlen($nis) > 20) {
                $errors[] = "Baris ke-{$rowNumber}: NIS '{$nis}' wajib diisi dan maksimal 20 karakter.";
                continue;
            }

            // Cek duplikasi NIS di dalam berkas CSV
            if (in_array($nis, $batchNis)) {
                $errors[] = "Baris ke-{$rowNumber}: NIS '{$nis}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Cek duplikasi NIS di database profil siswa
            if (StudentProfile::where('nis', $nis)->exists()) {
                $errors[] = "Baris ke-{$rowNumber}: NIS '{$nis}' sudah terdaftar di profil siswa.";
                continue;
            }

            // Cek duplikasi Username di database users
            if (User::where('username', $nis)->exists()) {
                $errors[] = "Baris ke-{$rowNumber}: Akun dengan username NIS '{$nis}' sudah terdaftar di sistem.";
                continue;
            }

            // Normalisasi pilihan Jenis Kelamin
            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$genderRaw}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            // Validasi Kelas jika kolom Kelas diisi (Dukungan penuh untuk seluruh kelas 7, 8, dan 9)
            $classRoomId = null;
            if (!empty($className)) {
                $rawClassKey   = strtolower(trim($className));
                $cleanClassKey = preg_replace('/\s+|-/', '', $rawClassKey);
                $arabicKey     = str_replace(['viii', 'vii', 'ix', 'vi', 'x'], ['8', '7', '9', '6', '10'], $cleanClassKey);
                $romanKey      = str_replace(['8', '7', '9', '6', '10'], ['viii', 'vii', 'ix', 'vi', 'x'], $cleanClassKey);

                if (isset($classMap[$rawClassKey])) {
                    $classRoomId = $classMap[$rawClassKey];
                } elseif (isset($classMap[$cleanClassKey])) {
                    $classRoomId = $classMap[$cleanClassKey];
                } elseif (isset($classMap[$arabicKey])) {
                    $classRoomId = $classMap[$arabicKey];
                } elseif (isset($classMap[$romanKey])) {
                    $classRoomId = $classMap[$romanKey];
                } else {
                    $availableNames = $allClasses->pluck('name')->implode(', ');
                    $errors[] = "Baris ke-{$rowNumber}: Kelas '{$className}' tidak ditemukan di sistem. Pilihan kelas yang terdaftar: {$availableNames}.";
                    continue;
                }

                if (!$activeYear) {
                    $errors[] = "Baris ke-{$rowNumber}: Gagal mengalokasikan kelas '{$className}' karena belum ada Tahun Ajaran aktif.";
                    continue;
                }
            }

            // Catat ke daftar data yang siap disimpan
            $batchNis[] = $nis;

            $studentsToInsert[] = [
                'full_name'     => $fullName,
                'nis'           => $nis,
                'gender'        => $gender,
                'phone_number'  => $phoneNumber ?: null,
                'parent_phone'  => $parentPhone ?: null,
                'class_room_id' => $classRoomId,
            ];
        }

        // Jika terdapat baris yang bermasalah, kembalikan dengan pesan kesalahan lengkap
        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->with('error', 'Proses impor dibatalkan karena ditemukan kesalahan pada format data. Silakan periksa rincian kesalahan di bawah ini.');
        }

        // Pastikan ada setidaknya satu baris data valid untuk diimpor
        if (empty($studentsToInsert)) {
            return redirect()->back()->with('error', 'Berkas Excel tidak memuat data siswa yang dapat diimpor.');
        }

        // 4. Eksekusi penyimpanan ke basis data menggunakan Database Transaction
        DB::transaction(function () use ($studentsToInsert, $activeYear) {
            foreach ($studentsToInsert as $studentData) {
                // Membuat akun pengguna siswa dengan username NIS
                $user = User::create([
                    'username'             => $studentData['nis'],
                    'password'             => 'siswasmpsmi',
                    'must_change_password' => true,
                    'role'                 => 'siswa',
                ]);

                // Membuat profil siswa terkait
                StudentProfile::create([
                    'user_id'      => $user->id,
                    'full_name'    => $studentData['full_name'],
                    'nis'          => $studentData['nis'],
                    'gender'       => $studentData['gender'],
                    'phone_number' => $studentData['phone_number'],
                    'parent_phone' => $studentData['parent_phone'],
                    'status'       => 'aktif',
                ]);

                // Jika kolom kelas diisi, daftarkan langsung ke rombel kelas tersebut pada tahun ajaran aktif
                if (!empty($studentData['class_room_id']) && $activeYear) {
                    ClassEnrollment::create([
                        'student_id'       => $user->id,
                        'class_room_id'    => $studentData['class_room_id'],
                        'academic_year_id' => $activeYear->id,
                        'status'           => 'aktif',
                    ]);
                }
            }
        });

        $total = count($studentsToInsert);
        return redirect()->route('students.index')->with('success', "Alhamdulillah! Sebanyak {$total} data siswa berhasil diimpor ke sistem.");
    }
}
