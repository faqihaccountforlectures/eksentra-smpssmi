<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk pembaruan (update) data Siswa.
     */
    public function rules(): array
    {
        // Mengambil ID akun dari parameter URL
        $userId = $this->route('id');
        
        // Mengambil data siswa beserta profilnya untuk mendapatkan ID profil
        $student = User::with('studentProfile')->find($userId);
        $profileId = $student->studentProfile->id ?? null;

        return [
            'full_name' => 'required|string|max:255',
            
            // Pengecualian ID profil untuk validasi keunikan NIS
            'nis' => 'required|string|max:20|unique:student_profiles,nis,' . $profileId,
            
            'gender' => 'required|in:laki-laki,perempuan',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'parent_phone' => 'required|string|max:20',
        ];
    }

    /**
     * Menyediakan pesan kesalahan khusus (Custom Error Messages) dalam bahasa Indonesia formal.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS tersebut sudah digunakan oleh siswa lain.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'phone_number.required' => 'Nomor telepon siswa wajib diisi.',
            'parent_phone.required' => 'Nomor telepon orang tua wajib diisi.',
        ];
    }
}
