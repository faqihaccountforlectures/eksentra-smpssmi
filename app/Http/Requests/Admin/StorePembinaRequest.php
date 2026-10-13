<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePembinaRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk penambahan data Pembina baru.
     */
    public function rules(): array
    {
        return [
            // Alamat email diwajibkan, berformat email, dan harus belum pernah didaftarkan di tabel users
            'email' => 'required|email|unique:users,email',
            
            // Kode pembina / guru opsional
            'teacher_code' => 'nullable|string|max:10|unique:teacher_profiles,teacher_code',
            'pembina_code' => 'nullable|string|max:10|unique:teacher_profiles,teacher_code',

            // Nama lengkap diwajibkan dengan batas maksimal 255 karakter
            'full_name' => 'required|string|max:255',
            
            // NIP diwajibkan, maksimal 50 karakter
            'nip' => 'required|string|max:50',
            
            // Jenis kelamin diwajibkan, hanya menerima nilai yang telah ditentukan
            'gender' => 'required|in:laki-laki,perempuan',
            
            // Jabatan diwajibkan
            'position' => 'required|in:guru,kepala_sekolah,wakil_kepala_sekolah',
            
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
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar pada sistem. Silakan gunakan alamat email lain.',
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
