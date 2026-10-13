<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePembinaRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk pembaruan (update) data Pembina.
     */
    public function rules(): array
    {
        // Mengambil ID pengguna (user) yang sedang diperbarui dari parameter URL
        $userId = $this->route('id');

        return [
            'email' => 'required|email|unique:users,email,' . $userId,
            
            // Kode pembina unik, mengecualikan user yang sedang diedit
            'teacher_code' => ['nullable', 'string', 'max:10', Rule::unique('teacher_profiles', 'teacher_code')->ignore($userId, 'user_id')],
            'pembina_code' => ['nullable', 'string', 'max:10', Rule::unique('teacher_profiles', 'teacher_code')->ignore($userId, 'user_id')],

            'full_name' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'gender' => 'required|in:laki-laki,perempuan',
            'position' => 'required|in:guru,kepala_sekolah,wakil_kepala_sekolah',
            'phone_number' => 'required|string|max:20',
        ];
    }

    /**
     * Menyediakan pesan kesalahan khusus (Custom Error Messages) dalam bahasa Indonesia formal.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah digunakan oleh pengguna lain.',
            'full_name.required' => 'Nama lengkap pembina wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'position.required' => 'Jabatan wajib dipilih.',
            'position.in' => 'Pilihan jabatan tidak valid.',
            'phone_number.required' => 'Nomor WA / Telepon wajib diisi.',
        ];
    }
}
