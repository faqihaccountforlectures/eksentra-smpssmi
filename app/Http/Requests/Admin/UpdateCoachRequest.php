<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk pembaruan (update) data Coach.
     */
    public function rules(): array
    {
        // Mengambil ID pengguna (user) yang sedang diperbarui dari parameter URL
        $userId = $this->route('id');

        return [
            'username' => 'required|string|max:100|unique:users,username,' . $userId,
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:laki-laki,perempuan',
            'phone_number' => 'required|string|max:20',
        ];
    }

    /**
     * Menyediakan pesan kesalahan khusus (Custom Error Messages) dalam bahasa Indonesia formal.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username pembina wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh pengguna lain.',
            'full_name.required' => 'Nama lengkap pembina wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'phone_number.required' => 'Nomor WA / Telepon wajib diisi.',
        ];
    }
}
