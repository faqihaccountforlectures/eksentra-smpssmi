<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk penambahan data Siswa baru.
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            
            // NIS bersifat wajib dan harus belum terdaftar di tabel profil siswa
            'nis' => 'required|string|max:20|unique:student_profiles,nis',
            
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
            'nis.unique' => 'NIS tersebut sudah terdaftar untuk siswa lain.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'phone_number.required' => 'Nomor telepon siswa wajib diisi.',
            'parent_phone.required' => 'Nomor telepon orang tua wajib diisi.',
        ];
    }
}
