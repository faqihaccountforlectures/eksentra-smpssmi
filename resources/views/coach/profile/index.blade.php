@extends('layouts.app')

@section('title', 'Profil Pembina')
@section('header', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- BAGIAN 1: Kartu Profil Utama (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Identitas Diri -->
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden text-center relative">
                <!-- Cover Banner Navy -->
                <div class="h-28 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                </div>
                
                <div class="px-6 pb-6 relative">
                    <!-- Avatar Inisial Pembina -->
                    <div class="w-24 h-24 mx-auto rounded-full bg-white border-2 border-blue-100 shadow-md flex items-center justify-center -mt-12 mb-3 overflow-hidden p-1">
                        <div class="w-full h-full bg-blue-800 text-white flex items-center justify-center font-heading font-bold text-3xl rounded-full">
                            {{ substr($user->coachProfile->full_name ?? ($user->name ?? 'P'), 0, 1) }}
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 font-heading leading-tight mb-1">
                        {{ $user->coachProfile->full_name ?? 'Data Belum Lengkap' }}
                    </h3>
                    
                    <div class="inline-block px-3 py-1 bg-blue-50 text-blue-800 font-bold text-xs rounded-lg border border-blue-200 mb-4">
                        Pembina Ekstrakurikuler
                    </div>
                    
                    <div class="space-y-3 text-left">
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Email Akses</p>
                            <p class="font-semibold text-slate-800 text-xs truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: Data & Form Kontak (Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Pembina (Read-Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="bg-blue-50 p-2.5 rounded-xl text-blue-800 border border-blue-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 font-heading">Informasi Profil</h3>
                        <p class="text-xs text-slate-500">Data identitas resmi pembina ekstrakurikuler.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->coachProfile->full_name ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                        @php
                            $gender = strtolower($user->coachProfile->gender ?? '');
                            $genderText = '-';
                            if ($gender === 'l' || $gender === 'laki-laki') $genderText = 'Laki-laki';
                            elseif ($gender === 'p' || $gender === 'perempuan') $genderText = 'Perempuan';
                        @endphp
                        <input type="text" value="{{ $genderText }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Akun</label>
                        <input type="text" value="{{ $user->email }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold rounded-xl px-4 py-2.5 text-xs cursor-not-allowed">
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-5 italic flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    * Apabila terdapat kekeliruan data profil, silakan hubungi Administrator Sekolah.
                </p>
            </div>

            <!-- Formulir Pembaruan Kontak -->
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center gap-3">
                    <div class="bg-blue-50 p-2 rounded-lg text-blue-800 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base font-heading">Pengaturan Nomor Kontak</h3>
                </div>
                
                <form action="{{ route('coach.profile.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                        <div class="relative max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->coachProfile->phone_number ?? '') }}" placeholder="Contoh: 081234567890" class="w-full pl-10 bg-slate-50 border @error('phone_number') border-rose-400 ring-rose-500/20 @else border-slate-200 focus:border-blue-700 focus:bg-white focus:ring-blue-100 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 transition-all outline-none focus:ring-2 font-mono font-medium shadow-sm">
                        </div>
                        
                        @error('phone_number')
                            <p class="text-rose-600 text-xs font-bold mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-slate-500 text-xs mt-2">Nomor kontak ini digunakan oleh admin dan siswa untuk keperluan koordinasi kegiatan.</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-xl text-xs active:scale-95 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Perubahan Kontak</span>
                    </button>
                </form>
            </div>
            
        </div>
    </div>

</div>
@endsection
