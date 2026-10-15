@extends('layouts.app')

@section('title', 'Dasbor Siswa')
@section('header', 'Dasbor Siswa')

@section('content')
    <!-- 1. KARTU SAMBUTAN HERO SISWA: Gradasi Biru Tua Modern -->
    <div class="rounded-2xl p-5 sm:p-6 text-white mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-950 shadow-md group">
        <!-- Ornamen Halus -->
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-6 bottom-0 opacity-10 pointer-events-none">
            <svg class="w-36 h-36 text-white transform translate-y-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-900/80 border border-blue-700/60 text-blue-200 text-[10px] font-mono font-bold tracking-wider mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="uppercase">PORTAL SISWA</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-heading tracking-tight">Halo, {{ $user->studentProfile->full_name ?? $user->name }}! 👋</h2>
            <p class="text-xs text-blue-100 mt-1.5">
                Selamat datang di Portal Ekstrakurikuler SMP Science Mutiara Insani.
            </p>
            
            <!-- Badges Informasi Diri Siswa -->
            <div class="flex flex-wrap gap-2 mt-3">
                <div class="bg-blue-900/80 border border-blue-700/60 px-3 py-1 rounded-lg flex items-center gap-2 shadow-sm text-blue-100">
                    <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <span class="font-bold text-[11px] font-mono tracking-wider text-blue-100">NIS: {{ $user->studentProfile->nis ?? 'Belum Diatur' }}</span>
                </div>
                <div class="bg-blue-900/80 border border-blue-700/60 px-3 py-1 rounded-lg flex items-center gap-2 shadow-sm text-blue-100">
                    <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-bold text-[11px] text-blue-100">Kelas: {{ $className }}</span>
                </div>
            </div>
        </div>

        <!-- Tanggal & Jam Sistem -->
        <div class="relative z-10 flex items-center gap-2 bg-blue-900/80 border border-blue-700/60 px-3.5 py-2 rounded-xl text-white text-xs shrink-0 font-mono">
            <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-white font-semibold">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Pengingat Tunggakan Pembayaran (Muncul jika ada tunggakan) -->
    @if($hasUnpaidBills)
    <div class="bg-rose-50/60 border border-rose-200 rounded-2xl p-5 mb-6 flex items-start gap-4 shadow-sm relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">
        <div class="bg-rose-100 border border-rose-200 p-2.5 rounded-xl text-rose-600 shrink-0 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 relative z-10">
            <h3 class="text-rose-800 font-bold text-sm sm:text-base font-heading mb-1">Peringatan Iuran Ekstrakurikuler</h3>
            <p class="text-slate-600 text-xs leading-relaxed mb-3">Anda memiliki iuran ekstrakurikuler yang belum terverifikasi atau belum dibayarkan. Silakan lakukan pembayaran dan upload bukti transfer.</p>
            <a href="{{ route('siswa.payments.index') }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl active:scale-95 transition-all shadow-sm">
                <span>Upload Bukti Bayar</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
    @endif

    <!-- Grid Statistik Siswa -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider font-mono">Ekskul Aktif</p>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $approvedRegistrations->count() }} <span class="text-xs font-normal text-slate-500 lowercase">kegiatan</span></h3>
                <a href="{{ route('siswa.extracurriculars.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 mt-2 transition-colors">
                    <span>Katalog Ekskul</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center border border-blue-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-amber-200 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider font-mono">Menunggu Persetujuan</p>
                <h3 class="text-2xl font-black text-amber-800 font-heading mt-1">{{ $pendingRegistrations->count() }} <span class="text-xs font-normal text-slate-500 lowercase">pengajuan</span></h3>
                <span class="text-[11px] font-semibold text-amber-700 inline-block mt-2">Diverifikasi Admin</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider font-mono">Status Pembayaran</p>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $hasUnpaidBills ? 'Ada Tunggakan' : 'Lancar' }}</h3>
                <a href="{{ route('siswa.payments.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1 mt-2 transition-colors">
                    <span>Riwayat Pembayaran</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Grid Konten: Ekstrakurikuler Aktif & Riwayat Pembayaran -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        <!-- KOLOM KIRI: Ekstrakurikuler yang Diikuti & Nilai -->
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col group hover:-translate-y-0.5 hover:shadow-md hover:border-blue-300 transition-all duration-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 p-2.5 rounded-xl text-blue-800 border border-blue-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base font-heading tracking-wide">Ekstrakurikuler & Penilaian</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5 font-medium tracking-wide">Kegiatan yang aktif diikuti beserta evaluasi nilai dari pembina ekstrakurikuler.</p>
                    </div>
                </div>
                <a href="{{ route('siswa.extracurriculars.index') }}" class="text-xs font-bold text-blue-800 hover:text-blue-900 transition-colors">Lihat Semua</a>
            </div>

            <div class="p-6 flex-1">
                @if($approvedRegistrations->count() > 0)
                    <div class="space-y-3.5">
                        @foreach($approvedRegistrations as $reg)
                        <div class="border border-slate-200 rounded-xl p-4 hover:border-blue-300 hover:bg-blue-50/30 transition-all bg-white relative overflow-hidden group/item shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center shrink-0 overflow-hidden border border-blue-200">
                                        @if($reg->extracurricular->image)
                                            <img src="{{ asset('storage/' . $reg->extracurricular->image) }}" alt="Foto" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 text-base truncate font-heading">{{ $reg->extracurricular->name }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5 font-medium">
                                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="truncate">{{ $reg->extracurricular->schedule }}</span>
                                        </p>
                                        <p class="text-[11px] text-blue-800 font-semibold mt-1">
                                            Pembina: {{ $reg->extracurricular->coach->display_name ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Nilai Badges (Ganjil & Genap) -->
                                @php
                                    $activeYearGrades = $activeYear ? $reg->grades->where('academic_year_id', $activeYear->id) : collect();
                                    $displayGrades = $activeYearGrades->count() > 0 ? $activeYearGrades : ($reg->grades->groupBy('academic_year_id')->last() ?? collect());
                                    $ganjil = $displayGrades->firstWhere('semester', 'ganjil');
                                    $genap = $displayGrades->firstWhere('semester', 'genap');
                                    $yearLabel = $displayGrades->first() ? ($displayGrades->first()->academicYear->year_name ?? '') : ($activeYear ? $activeYear->year_name : '');
                                @endphp
                                <div class="shrink-0 flex flex-col items-end gap-1">
                                    @if($ganjil || $genap)
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider font-mono">TA {{ $yearLabel }}</span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded-lg {{ $ganjil ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-400' }} font-bold text-[10px]" title="{{ $ganjil && $ganjil->notes ? 'Catatan: ' . $ganjil->notes : '' }}">
                                                Ganjil: {{ $ganjil ? $ganjil->grade . ($ganjil->score !== null ? " ({$ganjil->score})" : '') : '-' }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg {{ $genap ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-400' }} font-bold text-[10px]" title="{{ $genap && $genap->notes ? 'Catatan: ' . $genap->notes : '' }}">
                                                Genap: {{ $genap ? $genap->grade . ($genap->score !== null ? " ({$genap->score})" : '') : '-' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum dinilai</span>
                                    @endif
                                </div>
                            </div>

                            @php
                                $notesGrade = ($genap && $genap->notes) ? $genap : (($ganjil && $ganjil->notes) ? $ganjil : null);
                            @endphp
                            @if($notesGrade && $notesGrade->notes)
                            <div class="mt-3 pt-2.5 border-t border-slate-100 text-xs">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Catatan Pembina (Sem. {{ ucfirst($notesGrade->semester) }}):</p>
                                <p class="text-slate-800 italic mt-0.5">"{{ $notesGrade->notes }}"</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center py-10">
                        <div class="w-14 h-14 bg-blue-50 text-blue-700 rounded-full flex items-center justify-center mb-3 border border-blue-200">
                            <svg class="w-7 h-7 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 text-base font-heading mb-1">Belum Bergabung Ekskul</h4>
                        <p class="text-xs text-slate-500 font-medium mb-4 max-w-xs leading-relaxed">Anda belum memiliki kegiatan ekstrakurikuler yang aktif. Telusuri katalog dan daftar kegiatan yang Anda sukai.</p>
                        <a href="{{ route('siswa.extracurriculars.index') }}" class="px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                            Telusuri Katalog Ekskul
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Riwayat Pembayaran Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col group hover:-translate-y-0.5 hover:shadow-md hover:border-blue-300 transition-all duration-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 p-2.5 rounded-xl text-blue-800 border border-blue-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base font-heading tracking-wide">Riwayat Pembayaran Terbaru</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5 font-medium tracking-wide">Status verifikasi bukti transfer iuran ekskul.</p>
                    </div>
                </div>
                <a href="{{ route('siswa.payments.index') }}" class="text-xs font-bold text-blue-800 hover:text-blue-900 transition-colors">Lihat Semua</a>
            </div>
            
            <div class="p-6 flex-1">
                @if($recentPayments->count() > 0)
                    <div class="divide-y divide-slate-100 text-xs">
                        @foreach($recentPayments as $payment)
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-slate-800 text-sm font-heading">{{ $payment->extracurricular->name ?? 'Iuran Ekskul' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Bulan: {{ $payment->month }} {{ $payment->year }} • Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                @if($payment->payment_status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Terverifikasi
                                    </span>
                                @elseif($payment->payment_status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center py-10">
                        <div class="w-14 h-14 bg-blue-50 text-blue-700 rounded-full flex items-center justify-center mb-3 border border-blue-200">
                            <svg class="w-7 h-7 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 text-base font-heading mb-1">Belum Ada Riwayat Pembayaran</h4>
                        <p class="text-xs text-slate-500 font-medium mb-4 max-w-xs leading-relaxed">Belum ada transaksi pembayaran atau upload bukti transfer yang dilakukan.</p>
                        <a href="{{ route('siswa.payments.index') }}" class="px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                            Upload Bukti Pembayaran
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection