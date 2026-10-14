@extends('layouts.app')

@section('title', 'Dasbor Pembina Ekstrakurikuler')
@section('header', 'Dasbor Pembina')

@section('content')
    <!-- 1. KARTU SAMBUTAN HERO PEMBINA: Desain Modern, Padat, Glow in the dark -->
    <div class="glow-card rounded-xl p-4 sm:p-5 text-white mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden border border-cyan-500/25 group">
        <!-- Ornamen Cahaya Neon -->
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-colors duration-500 pointer-events-none"></div>
        <div class="absolute right-6 bottom-0 opacity-5 pointer-events-none">
            <svg class="w-36 h-36 text-cyan-400 transform translate-y-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-950/80 border border-emerald-400/30 text-emerald-300 text-[10px] font-mono font-bold tracking-wider mb-2 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="uppercase">PORTAL PEMBINA</span>
            </div>
            <h2 class="text-lg sm:text-xl font-black text-white font-heading tracking-tight">Selamat Datang, {{ $user->coachProfile->full_name ?? 'Bapak/Ibu Pembina' }}! 👋</h2>
            <p class="text-xs text-slate-300/90 mt-0.5">
                Kelola kegiatan bimbingan dan evaluasi penilaian capaian siswa.
            </p>
            
            <!-- Badges Informasi Pembina -->
            <div class="flex flex-wrap gap-2 mt-3">
                <div class="bg-[#070b14]/80 border border-emerald-500/30 px-3 py-1 rounded-lg flex items-center gap-2 backdrop-blur-sm shadow-sm text-emerald-300">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    <span class="font-bold text-[11px] tracking-wide">Peran: Pembina Ekstrakurikuler</span>
                </div>
            </div>
        </div>

        <!-- Tanggal & Jam Sistem -->
        <div class="relative z-10 flex items-center gap-2 bg-[#070b14]/60 border border-cyan-500/20 px-3 py-1.5 rounded-lg text-slate-300 text-xs shrink-0 font-mono">
            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Grid Statistik Utama (4 Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Kartu 1: Total Ekstrakurikuler Binaan -->
        <div class="glow-card p-5 rounded-2xl border border-cyan-500/20 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-cyan-500/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-cyan-400/80 uppercase tracking-wider">Ekskul Binaan</p>
                <h3 class="text-2xl font-black text-white font-heading mt-1">{{ $totalExtracurriculars }} <span class="text-xs font-normal text-slate-400 lowercase">kegiatan</span></h3>
                <a href="{{ route('coach.extracurriculars.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Daftar Ekskul</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/30 group-hover:scale-110 transition-transform shrink-0 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>

        <!-- Kartu 2: Total Siswa Aktif -->
        <div class="glow-card p-5 rounded-2xl border border-emerald-500/20 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-emerald-400/80 uppercase tracking-wider">Total Anggota Siswa</p>
                <h3 class="text-2xl font-black text-white font-heading mt-1">{{ $totalMembers }} <span class="text-xs font-normal text-slate-400 lowercase">siswa aktif</span></h3>
                <span class="text-[11px] font-medium text-emerald-400 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></span>
                    Disetujui Admin
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/30 group-hover:scale-110 transition-transform shrink-0 shadow-[0_0_15px_rgba(52,211,153,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Siswa Sudah Dinilai -->
        <div class="glow-card p-5 rounded-2xl border border-sky-500/20 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-sky-500/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-sky-400/80 uppercase tracking-wider">Siswa Sudah Dinilai</p>
                <h3 class="text-2xl font-black text-white font-heading mt-1">{{ $totalGraded }} <span class="text-xs font-normal text-slate-400 lowercase">/ {{ $totalMembers }} siswa</span></h3>
                <span class="text-[11px] font-medium text-sky-300 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-sky-400 shadow-[0_0_6px_rgba(56,189,248,0.8)]"></span>
                    {{ $totalMembers > 0 ? round(($totalGraded / $totalMembers) * 100) : 0 }}% Lengkap
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/30 group-hover:scale-110 transition-transform shrink-0 shadow-[0_0_15px_rgba(56,189,248,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Kartu 4: Pendaftaran Menunggu Approval -->
        <div class="glow-card p-5 rounded-2xl border border-amber-500/20 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-amber-400/80 uppercase tracking-wider">Approval</p>
                <h3 class="text-2xl font-black text-white font-heading mt-1">{{ $totalPending }} <span class="text-xs font-normal text-slate-400 lowercase">pendaftar</span></h3>
                <span class="text-[11px] font-medium text-amber-400 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-[0_0_6px_rgba(251,191,36,0.8)]"></span>
                    Menunggu verifikasi admin
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/30 group-hover:scale-110 transition-transform shrink-0 shadow-[0_0_15px_rgba(251,191,36,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        
    </div>

    <!-- Grid Layout Utama: Daftar Ekskul Binaan & Anggota Terbaru -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI (2/3): Ekstrakurikuler yang Dibina -->
        <div class="xl:col-span-2 glow-card rounded-2xl border border-cyan-500/20 overflow-hidden flex flex-col">
            <div class="px-7 py-5 border-b border-cyan-500/15 bg-[#0d1527]/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-cyan-500/10 p-2.5 rounded-xl text-cyan-400 border border-cyan-500/30 shadow-[0_0_12px_rgba(6,182,212,0.15)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-white text-base font-heading tracking-wide">Ekstrakurikuler Binaan Saya</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium tracking-wide">Kelola anggota dan input nilai untuk masing-masing kegiatan.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-cyan-300 bg-cyan-500/10 px-3.5 py-1.5 rounded-lg border border-cyan-500/30 shadow-[0_0_10px_rgba(6,182,212,0.1)]">
                    {{ $myExtracurriculars->count() }} Ekskul Aktif
                </span>
            </div>

            <div class="p-7 flex-1">
                @if($myExtracurriculars->count() > 0)
                    <div class="space-y-4">
                        @foreach($myExtracurriculars as $ekskul)
                        <div class="bg-[#070b14]/60 border border-cyan-500/15 rounded-xl p-5 hover:border-cyan-400/30 hover:bg-[#0d1527]/60 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="font-extrabold text-white text-lg font-heading">{{ $ekskul->name }}</h4>
                                <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs">
                                    <span class="inline-flex items-center gap-1.5 text-slate-400 font-medium">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $ekskul->schedule }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-emerald-300 font-bold bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/30">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ $ekskul->approved_count }} Anggota Aktif
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-sky-300 font-medium bg-sky-500/10 px-2 py-0.5 rounded border border-sky-500/30">
                                        {{ $ekskul->graded_count }} / {{ $ekskul->approved_count }} Dinilai
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('coach.extracurriculars.show', $ekskul->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.25)] hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-all hover:scale-105 active:scale-95">
                                    <span>Input & Lihat Nilai</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <div class="w-16 h-16 bg-cyan-500/10 text-cyan-400 rounded-full flex items-center justify-center mb-4 border border-cyan-500/20 shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h4 class="font-extrabold text-white text-lg font-heading mb-1">Belum Ada Ekstrakurikuler Binaan</h4>
                        <p class="text-xs text-slate-400 font-medium max-w-sm leading-relaxed">Anda belum ditugaskan sebagai pembina pada ekstrakurikuler mana pun. Silakan hubungi Administrator untuk penugasan pembina.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN (1/3): Anggota Terbaru & Profil Singkat -->
        <div class="space-y-6">
            
            <!-- Kartu Anggota Siswa Terbaru -->
            <div class="glow-card rounded-2xl p-6 border border-cyan-500/20">
                <h3 class="font-extrabold text-white text-base font-heading mb-4 pb-3 border-b border-cyan-500/15 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Anggota Siswa Terbaru
                    </span>
                </h3>
                
                @if($recentMembers->count() > 0)
                    <div class="divide-y divide-cyan-500/10 text-xs">
                        @foreach($recentMembers as $member)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-600 to-blue-700 text-white flex items-center justify-center font-bold text-xs shrink-0 font-heading shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                                    {{ substr($member->student->studentProfile->full_name ?? ($member->student->name ?? 'S'), 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-white truncate">{{ $member->student->studentProfile->full_name ?? $member->student->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $member->extracurricular->name }}</p>
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
                                            <span class="px-1.5 py-0.5 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 font-bold text-[9px]" title="Semester Ganjil: {{ $mGanjil->grade }} ({{ $mGanjil->score }})">Ganjil: {{ $mGanjil->grade }}</span>
                                        @endif
                                        @if($mGenap)
                                            <span class="px-1.5 py-0.5 rounded bg-teal-500/20 text-teal-300 border border-teal-500/30 font-bold text-[9px]" title="Semester Genap: {{ $mGenap->grade }} ({{ $mGenap->score }})">Genap: {{ $mGenap->grade }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-500 italic">Belum dinilai</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada siswa anggota yang terdaftar.</p>
                @endif
            </div>

            <!-- Kartu Info Pembina -->
            <div class="glow-card rounded-2xl p-6 border border-cyan-500/20 relative overflow-hidden">
                <!-- Ornamen glow -->
                <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-600 to-blue-700 text-white flex items-center justify-center font-extrabold font-heading text-lg shrink-0 shadow-[0_0_15px_rgba(6,182,212,0.35)]">
                        {{ substr($user->coachProfile->full_name ?? ($user->name ?? 'G'), 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-extrabold text-white text-sm truncate font-heading">{{ $user->coachProfile->full_name ?? $user->name }}</h4>
                        <p class="text-[11px] text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-3 border-t border-cyan-500/15 text-xs relative z-10">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Nomor WhatsApp:</span>
                        <span class="font-bold text-cyan-300 font-mono">{{ $user->coachProfile->phone_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Jenis Kelamin:</span>
                        @php
                            $g = strtolower($user->coachProfile->gender ?? '');
                            $gLabel = '-';
                            if ($g === 'l' || $g === 'laki-laki') $gLabel = 'Laki-laki';
                            elseif ($g === 'p' || $g === 'perempuan') $gLabel = 'Perempuan';
                        @endphp
                        <span class="font-bold text-white">{{ $gLabel }}</span>
                    </div>
                </div>

                <a href="{{ route('coach.profile.index') }}" class="mt-4 w-full block text-center py-2 px-4 bg-gradient-to-r from-cyan-600/20 to-blue-600/20 hover:from-cyan-600/30 hover:to-blue-600/30 text-cyan-300 font-bold text-xs rounded-xl border border-cyan-500/30 hover:border-cyan-400/50 transition-all relative z-10">
                    Perbarui Profil
                </a>
            </div>

        </div>

    </div>
@endsection
