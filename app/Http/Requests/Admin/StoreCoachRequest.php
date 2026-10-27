<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk penambahan data Coach baru.
     */
    public function rules(): array
    {
        return [
            // Username pembina diwajibkan dan harus unik di tabel users
            'username' => 'required|string|max:100|unique:users,username',
            
            // Nama lengkap diwajibkan dengan batas maksimal 255 karakter
            'full_name' => 'required|string|max:255',
            
            // Jenis kelamin diwajibkan
            'gender' => 'required|in:laki-laki,perempuan',

            // Tanggal lahir diwajibkan untuk kata sandi default pembina (YYYYMMDD)
            'birth_date' => 'required|date',
            
            // Nomor telepon diwajibkan, maksimal 20 karakter
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
            'username.unique' => 'Username tersebut sudah terdaftar pada sistem. Silakan gunakan username lain.',
            'full_name.required' => 'Nama lengkap pembina wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'birth_date.required' => 'Tanggal lahir pembina wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir pembina tidak valid.',
            'phone_number.required' => 'Nomor WA / Telepon wajib diisi.',
        ];
    }
}
