<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoachProfile;
use App\Http\Requests\Admin\StoreCoachRequest;
use App\Http\Requests\Admin\UpdateCoachRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('coachProfile', function($cp) use ($search) {
                      $cp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $coaches = $query->latest()->paginate(10)->appends($request->all());

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
                'email' => $validatedData['email'],
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
                'email' => $validatedData['email'],
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

        $name = $coach->coachProfile->full_name ?? $coach->email;

        return redirect()->back()->with('success', "Kata sandi pembina {$name} berhasil di-reset ke default ('pembinasmpsmi'). Pembina diwajibkan membuat kata sandi baru saat login kembali.");
    }

    /**
     * Mengunduh berkas template CSV contoh untuk impor data coach massal.
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
                'Nama Lengkap',
                'Username / Email (@smpsmi.com)',
                'Jenis Kelamin',
                'Nomor Telepon',
            ], ';');

            // Baris data contoh
            fputcsv($output, [
                'Drs. H. Ahmad Subardjo, M.Pd.',
                'ahmad.subardjo@smpsmi.com',
                'Laki-laki',
                '081234567891',
            ], ';');

            fputcsv($output, [
                'Siti Rahmawati, S.Pd.',
                'siti.rahmawati@smpsmi.com',
                'Perempuan',
                '081234567892',
            ], ';');

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Memproses impor massal data coach dari berkas CSV/Excel.
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
        $coachesToInsert = [];
        $batchEmails = [];

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;

            if (empty(array_filter($row))) {
                continue;
            }

            if (count($row) < 3) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama Lengkap, Email, dan Jenis Kelamin.";
                continue;
            }

            $fullName    = trim($row[0] ?? '');
            $email       = trim($row[1] ?? '');
            $genderRaw   = strtolower(trim($row[2] ?? ''));
            $phoneNumber = trim(trim($row[3] ?? ''), "=\"' \t\n\r\0\x0B");

            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
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

            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$genderRaw}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            $batchEmails[] = $email;

            $coachesToInsert[] = [
                'full_name'    => $fullName,
                'email'        => $email,
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

        if (empty($coachesToInsert)) {
            return redirect()->back()->with('error', 'Berkas CSV tidak memuat data yang dapat diimpor.');
        }

        DB::transaction(function () use ($coachesToInsert) {
            foreach ($coachesToInsert as $coachData) {
                $user = User::firstOrCreate(
                    ['email' => $coachData['email']],
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
