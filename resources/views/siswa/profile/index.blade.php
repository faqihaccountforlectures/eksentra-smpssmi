@extends('layouts.app')

@section('title', 'Profil Siswa')
@section('header', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm animate-[fade-in-down_0.5s_ease-out]">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm animate-[fade-in-down_0.5s_ease-out]">
        <div class="w-8 h-8 rounded-xl bg-rose-100 border border-rose-200 text-rose-700 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- KARTU TUNGGAL BIODATA & KONTAK SISWA (MELEBAR KE SAMPING) -->
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
        <!-- Header Identitas Ringkas: Gradasi Biru Tua Modern seperti Dasbor -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 sm:py-6 bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-950 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-inner">
            <!-- Ornamen Halus Dasbor -->
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-6 bottom-0 opacity-10 pointer-events-none">
                <svg class="w-36 h-36 text-white transform translate-y-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
            </div>

            <!-- Kiri: Avatar + Nama + Peran -->
            <div class="relative z-10 flex items-center gap-4 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-white text-blue-900 flex items-center justify-center font-bold text-2xl font-heading shadow-md shrink-0 border-2 border-white/20">
                    {{ substr($profile->full_name ?? ($user->display_name ?? 'S'), 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold font-heading !text-white text-white" style="color: #ffffff !important;">{{ $profile->full_name ?? $user->display_name }}</h2>
                    <span class="inline-flex items-center gap-1.5 mt-1 px-3 py-1 bg-blue-900/80 border border-blue-700/60 text-blue-200 text-xs font-semibold rounded-lg shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        Siswa SMPS Science Mutiara Insani
                    </span>
                </div>
            </div>

            <!-- Kanan: Badge Status Siswa Aktif -->
            <div class="relative z-10 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-blue-900/80 border border-blue-700/60 text-white text-xs font-mono font-medium shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-semibold text-white !text-white" style="color: #ffffff !important;">Siswa Aktif</span>
            </div>
        </div>

        @php
            $gender = strtolower($profile->gender ?? '');
            $genderText = '-';
            if ($gender === 'l' || $gender === 'laki-laki') $genderText = 'Laki-laki';
            elseif ($gender === 'p' || $gender === 'perempuan') $genderText = 'Perempuan';
        @endphp

        <!-- Form Pembaruan Kontak & Tampilan Biodata (Melebar ke Samping) -->
        <form action="{{ route('siswa.profile.update') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Baris 1: Biodata Utama (3 Kolom) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Nama Lengkap Siswa -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa</label>
                    <input type="text" value="{{ $profile->full_name ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>

                <!-- Nomor Induk Siswa (NIS) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Induk Siswa (NIS)</label>
                    <input type="text" value="{{ $profile->nis ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold font-mono rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                    <input type="text" value="{{ $genderText }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>
            </div>

            <!-- Baris 2: Kontak Telepon (2 Kolom) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nomor Telepon Siswa -->
                <div>
                    <label for="phone_number" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Siswa</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $profile->phone_number ?? '') }}" placeholder="Contoh: 081234567890" class="w-full pl-10 bg-white border @error('phone_number') border-rose-400 ring-2 ring-rose-100 @else border-slate-300 focus:border-blue-700 focus:ring-2 focus:ring-blue-100 @enderror text-slate-800 font-medium font-mono rounded-xl px-4 py-2.5 text-xs outline-none transition-all shadow-sm">
                    </div>
                    @error('phone_number')
                        <p class="text-rose-600 text-xs font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon Orang Tua -->
                <div>
                    <label for="parent_phone" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Orang Tua</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <input type="text" id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $profile->parent_phone ?? '') }}" placeholder="Contoh: 081298765432" class="w-full pl-10 bg-white border @error('parent_phone') border-rose-400 ring-2 ring-rose-100 @else border-slate-300 focus:border-blue-700 focus:ring-2 focus:ring-blue-100 @enderror text-slate-800 font-medium font-mono rounded-xl px-4 py-2.5 text-xs outline-none transition-all shadow-sm">
                    </div>
                    @error('parent_phone')
                        <p class="text-rose-600 text-xs font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer: Petunjuk Admin di Kiri, Tombol Simpan di Kanan -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[11px] text-slate-500 italic flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    * Apabila terdapat kekeliruan biodata atau NIS, silakan hubungi Administrator Sekolah.
                </p>

                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs rounded-xl shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Perubahan Kontak</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
