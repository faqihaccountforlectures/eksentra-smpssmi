@extends('layouts.app')

@section('title', 'Profil Pembina')
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

    <!-- KARTU TUNGGAL BIODATA & KONTAK PEMBINA (MELEBAR KE SAMPING) -->
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
                    {{ substr($user->coachProfile->full_name ?? ($user->display_name ?? 'P'), 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold font-heading !text-white text-white" style="color: #ffffff !important;">{{ $user->coachProfile->full_name ?? $user->display_name }}</h2>
                    <span class="inline-flex items-center gap-1.5 mt-1 px-3 py-1 bg-blue-900/80 border border-blue-700/60 text-emerald-300 text-xs font-semibold rounded-lg shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Pembina Ekstrakurikuler
                    </span>
                </div>
            </div>

            <!-- Kanan: Badge Status Akun -->
            <div class="relative z-10 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-blue-900/80 border border-blue-700/60 text-white text-xs font-mono font-medium shadow-sm">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold text-white !text-white" style="color: #ffffff !important;">Akun Terverifikasi</span>
            </div>
        </div>

        @php
            $gender = strtolower($user->coachProfile->gender ?? '');
            $genderText = '-';
            if ($gender === 'l' || $gender === 'laki-laki') $genderText = 'Laki-laki';
            elseif ($gender === 'p' || $gender === 'perempuan') $genderText = 'Perempuan';
        @endphp

        <!-- Form Pembaruan Kontak & Tampilan Biodata (Grid Melebar ke Samping) -->
        <form action="{{ route('coach.profile.update') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Nama Lengkap & Gelar -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar</label>
                    <input type="text" value="{{ $user->coachProfile->full_name ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>

                <!-- Username Login -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Username Login</label>
                    <input type="text" value="{{ $user->email }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold font-mono rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                    <input type="text" value="{{ $genderText }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                </div>

                <!-- Nomor Telepon / WhatsApp -->
                <div>
                    <label for="phone_number" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WA <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->coachProfile->phone_number ?? '') }}" placeholder="Contoh: 081234567890" required class="w-full pl-10 bg-white border @error('phone_number') border-rose-400 ring-2 ring-rose-100 @else border-slate-300 focus:border-blue-700 focus:ring-2 focus:ring-blue-100 @enderror text-slate-800 font-medium font-mono rounded-xl px-4 py-2.5 text-xs outline-none transition-all shadow-sm">
                    </div>
                    @error('phone_number')
                        <p class="text-rose-600 text-xs font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer: Petunjuk Admin di Kiri, Tombol Simpan di Kanan -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[11px] text-slate-500 italic flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    * Apabila terdapat kekeliruan data profil atau nama gelar, silakan hubungi Administrator Sekolah.
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
