@extends('layouts.app')

@section('title', 'Tambah Data Pembina')
@section('header', 'Tambah Data Pembina')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Page -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('coaches.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border border-navy-light/30 text-gray-muted hover:text-navy-base hover:bg-navy-light/10 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-navy-dark font-heading tracking-wide">Registrasi Pembina Baru</h2>
                <p class="text-[13px] text-gray-muted mt-0.5 tracking-wide">Lengkapi biodata dan tentukan username pembina untuk akses login sistem.</p>
            </div>
        </div>
        
        <!-- Nampilin pesan error kalo ada isian yang salah -->
        @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm font-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('coaches.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-2xl shadow-sm border border-navy-light/30 overflow-hidden p-6 sm:p-8 flex flex-col">
                <div class="mb-6 flex items-center gap-3 pb-5 border-b border-navy-light/20">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-navy-dark font-heading">Biodata & Akun Pembina</h3>
                        <p class="text-xs text-gray-muted mt-0.5">Pastikan penulisan identitas dan username pembina sudah benar dan lengkap</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Lengkap & Gelar -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Contoh: Budi Santoso, S.Pd., M.Si." required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                    </div>

                    <!-- Username Pembina -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Username Pembina <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Contoh: ahmadfaqih" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                        <p class="text-[11px] text-gray-muted mt-1">Username ini digunakan pembina untuk login ke sistem.</p>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="gender" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 appearance-none shadow-sm font-medium cursor-pointer">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih jenis kelamin...</option>
                                <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                        <p class="text-[11px] text-gray-muted mt-1">Digunakan sebagai kata sandi awal pembina (format YYYYMMDD).</p>
                    </div>

                    <!-- Nomor HP / WA -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">No. WA / Telepon <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium font-mono">
                    </div>
                </div>

                <!-- Catatan Informasi Akses Login Pembina -->
                <div class="mt-6 bg-navy-light/10 border border-navy-light/30 rounded-xl p-4 flex gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-navy-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-xs text-navy-dark leading-relaxed">
                        <p class="font-bold text-navy-base mb-0.5">Informasi Akses Login Pembina</p>
                        <p class="text-gray-muted">
                            Password awal pembina otomatis diatur berupa <strong>tanggal lahir (format YYYYMMDD, contoh: 19850512)</strong>. Pembina akan login menggunakan username dan kata sandi default tersebut, lalu wajib menggantinya saat login pertama.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('coaches.index') }}" class="px-6 py-2.5 bg-white-off text-gray-muted font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:text-navy-dark border border-navy-light/30 transition-colors shadow-sm active:scale-95">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center gap-2 border border-transparent">
                    <span>Simpan Data Pembina</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
    </div>
@endsection
