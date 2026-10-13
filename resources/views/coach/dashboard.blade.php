@extends('layouts.app')

@section('title', 'Dasbor Pembina Ekstrakurikuler')
@section('header', 'Dasbor Pembina')

@section('content')
    <!-- Kartu Sambutan (Hero Banner) bertema Navy -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-8 text-white-off shadow-xl shadow-navy-dark/20 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden group">
        <!-- Ornamen Dekoratif Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        <div class="absolute right-0 bottom-0 opacity-10">
            <svg class="w-48 h-48 text-navy-light transform translate-x-8 translate-y-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2 font-heading tracking-wide">Selamat Datang, {{ $user->coachProfile->full_name ?? 'Bapak/Ibu Pembina' }}! 🎉</h2>
            <p class="text-navy-light text-base mb-4">Portal Ekstrakurikuler SMP Science Mutiara Insani. Kelola kegiatan dan penilaian siswa bimbingan Anda.</p>
            
            <!-- Badges Informasi Pembina -->
            <div class="flex flex-wrap gap-3">
                <div class="bg-navy-dark/50 border border-navy-light/30 px-3.5 py-1.5 rounded-lg flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    <svg class="w-4 h-4 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    <span class="font-bold text-xs text-white-off tracking-wide">Peran: Pembina Ekstrakurikuler</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Statistik Utama (4 Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Kartu 1: Total Ekstrakurikuler Binaan -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/40 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Ekskul Binaan</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalExtracurriculars }} <span class="text-xs font-normal text-gray-muted lowercase">kegiatan</span></h3>
                <a href="{{ route('coach.extracurriculars.index') }}" class="text-xs font-bold text-navy-base hover:text-navy-dark inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Daftar Ekskul</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>

        <!-- Kartu 2: Total Siswa Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-200/80 shadow-sm shadow-emerald-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-emerald-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Total Anggota Siswa</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalMembers }} <span class="text-xs font-normal text-gray-muted lowercase">siswa aktif</span></h3>
                <span class="text-[11px] font-medium text-emerald-600 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Disetujui Admin
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Siswa Sudah Dinilai -->
        <div class="bg-white p-5 rounded-2xl border border-blue-200/80 shadow-sm shadow-blue-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-blue-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Siswa Sudah Dinilai</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalGraded }} <span class="text-xs font-normal text-gray-muted lowercase">/ {{ $totalMembers }} siswa</span></h3>
                <span class="text-[11px] font-medium text-blue-600 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    {{ $totalMembers > 0 ? round(($totalGraded / $totalMembers) * 100) : 0 }}% Lengkap
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Kartu 4: Pendaftaran Menunggu Approval -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200/80 shadow-sm shadow-amber-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-amber-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Menunggu Approval</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalPending }} <span class="text-xs font-normal text-gray-muted lowercase">pendaftar</span></h3>
                <span class="text-[11px] font-medium text-amber-600 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu verifikasi admin
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        
    </div>

    <!-- Grid Layout Utama: Daftar Ekskul Binaan & Anggota Terbaru -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI (2/3): Ekstrakurikuler yang Dibina -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark text-base font-heading tracking-wide">Ekstrakurikuler Binaan Saya</h3>
                        <p class="text-[11px] text-gray-muted mt-0.5 font-bold tracking-wide">Kelola anggota dan input nilai untuk masing-masing kegiatan.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3.5 py-1.5 rounded-lg border border-navy-light/40">
                    {{ $myExtracurriculars->count() }} Ekskul Aktif
                </span>
            </div>

            <div class="p-7 flex-1">
                @if($myExtracurriculars->count() > 0)
                    <div class="space-y-4">
                        @foreach($myExtracurriculars as $ekskul)
                        <div class="bg-white-off/50 border border-navy-light/30 rounded-xl p-5 hover:border-navy-base/40 hover:bg-white transition-all duration-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-navy-dark text-lg font-heading">{{ $ekskul->name }}</h4>
                                <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs">
                                    <span class="inline-flex items-center gap-1.5 text-gray-muted font-medium">
                                        <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $ekskul->schedule }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ $ekskul->approved_count }} Anggota Aktif
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-blue-700 font-medium bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                        {{ $ekskul->graded_count }} / {{ $ekskul->approved_count }} Dinilai
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('coach.extracurriculars.show', $ekskul->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-navy-base hover:bg-navy-dark text-white-off font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105 active:scale-95">
                                    <span>Input & Lihat Nilai</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <div class="w-16 h-16 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-4 border border-navy-light/20 shadow-inner">
                            <svg class="w-8 h-8 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h4 class="font-bold text-navy-dark text-lg font-heading mb-1">Belum Ada Ekstrakurikuler Binaan</h4>
                        <p class="text-xs text-gray-muted font-medium max-w-sm leading-relaxed">Anda belum ditugaskan sebagai pembina pada ekstrakurikuler mana pun. Silakan hubungi Administrator untuk penugasan pembina.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN (1/3): Anggota Terbaru & Profil Singkat -->
        <div class="space-y-6">
            
            <!-- Kartu Anggota Siswa Terbaru -->
            <div class="bg-white rounded-2xl p-6 border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <h3 class="font-bold text-navy-dark text-base font-heading mb-4 pb-3 border-b border-navy-light/20 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Anggota Siswa Terbaru
                    </span>
                </h3>
                
                @if($recentMembers->count() > 0)
                    <div class="divide-y divide-navy-light/10 text-xs">
                        @foreach($recentMembers as $member)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold text-xs shrink-0 font-heading">
                                    {{ substr($member->student->studentProfile->full_name ?? ($member->student->name ?? 'S'), 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-dark truncate">{{ $member->student->studentProfile->full_name ?? $member->student->name }}</p>
                                    <p class="text-[10px] text-gray-muted truncate">{{ $member->extracurricular->name }}</p>
                                </div>
                            </div>
                            @php
                                $memberActiveGrades = $activeYear ? $member->grades->where('academic_year_id', $activeYear->id) : collect();
                                $mDisplayGrades = $memberActiveGrades->count() > 0 ? $memberActiveGrades : ($member->grades->groupBy('academic_year_id')->last() ?? collect());
                                $mGanjil = $mDisplayGrades->firstWhere('semester', 'ganjil');
                                $mGenap = $mDisplayGrades->firstWhere('semester', 'genap');
                            @endphp
                            <div class="shrink-0 text-right flex flex-col items-end gap-1">
                                @if($mGanjil || $mGenap)
                                    <div class="flex items-center gap-1">
                                        @if($mGanjil)
                                            <span class="px-1.5 py-0.5 rounded bg-navy-base text-white-off font-bold text-[9px]" title="Semester Ganjil: {{ $mGanjil->grade }} ({{ $mGanjil->score }})">Ganjil: {{ $mGanjil->grade }}</span>
                                        @endif
                                        @if($mGenap)
                                            <span class="px-1.5 py-0.5 rounded bg-navy-dark text-white-off font-bold text-[9px]" title="Semester Genap: {{ $mGenap->grade }} ({{ $mGenap->score }})">Genap: {{ $mGenap->grade }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[10px] text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-muted text-center py-6">Belum ada siswa anggota yang terdaftar.</p>
                @endif
            </div>

            <!-- Kartu Info Pembina -->
            <div class="bg-gradient-to-br from-white to-white-off rounded-2xl p-6 border border-navy-light/30 shadow-sm shadow-navy-base/5 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold font-heading text-lg shrink-0 shadow-inner">
                        {{ substr($user->coachProfile->full_name ?? ($user->name ?? 'G'), 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-navy-dark text-sm truncate font-heading">{{ $user->coachProfile->full_name ?? $user->name }}</h4>
                        <p class="text-[11px] text-gray-muted truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-3 border-t border-navy-light/20 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-muted font-medium">Nomor WhatsApp:</span>
                        <span class="font-bold text-navy-dark font-mono">{{ $user->coachProfile->phone_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-muted font-medium">Jenis Kelamin:</span>
                        @php
                            $g = strtolower($user->coachProfile->gender ?? '');
                            $gLabel = '-';
                            if ($g === 'l' || $g === 'laki-laki') $gLabel = 'Laki-laki';
                            elseif ($g === 'p' || $g === 'perempuan') $gLabel = 'Perempuan';
                        @endphp
                        <span class="font-bold text-navy-dark">{{ $gLabel }}</span>
                    </div>
                </div>

                <a href="{{ route('coach.profile.index') }}" class="mt-4 w-full block text-center py-2 px-4 bg-navy-light/10 hover:bg-navy-light/20 text-navy-base font-bold text-xs rounded-xl border border-navy-light/30 transition-colors">
                    Perbarui Profil
                </a>
            </div>

        </div>

    </div>
@endsection

