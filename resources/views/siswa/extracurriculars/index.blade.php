@extends('layouts.app')

@section('title', 'Katalog Ekstrakurikuler')
@section('header', 'Katalog Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses atau error -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-emerald-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-emerald-500 p-2 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-rose-500 p-2 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-white font-heading tracking-wide">Pilih Kegiatan Ekstrakurikuler</h2>
            <p class="text-xs text-slate-400 mt-0.5 tracking-wide font-medium">Daftarkan diri Anda ke kegiatan ekstrakurikuler yang sesuai dengan minat dan bakat.</p>
        </div>
    </div>

    <!-- Grid Katalog Ekskul -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($extracurriculars as $ekskul)
            @php
                $registration = $myRegistrations->get($ekskul->id);
                $status = $registration ? $registration->status : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-cyan-500/25 overflow-hidden flex flex-col group hover:-translate-y-1 hover:shadow-lg hover:border-cyan-400/40 transition-all duration-300">
                <!-- Header Kartu Foto / Gradien Gelap Neon -->
                <div class="h-36 relative overflow-hidden text-white flex flex-col justify-end p-5 group/header border-b border-cyan-500/20">
                    @if($ekskul->image)
                        <img src="{{ asset('storage/' . $ekskul->image) }}" class="absolute inset-0 w-full h-full object-cover group-hover/header:scale-105 transition-transform duration-500" alt="{{ $ekskul->name }}">
                        <!-- Overlay gradien hitam/navy agar teks nama ekskul kontras dan terbaca jelas -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#070b14]/95 via-[#070b14]/60 to-transparent"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#0c162d] via-[#09152b] to-[#070b14]"></div>
                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-cyan-500/15 rounded-full blur-2xl group-hover/header:bg-cyan-500/25 transition-colors duration-500"></div>
                        <div class="absolute left-4 top-4 w-16 h-16 bg-teal-500/10 rounded-full blur-xl"></div>
                    @endif
                    <h3 class="text-lg font-black font-heading tracking-wide relative z-10 drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)] text-white">{{ $ekskul->name }}</h3>
                </div>
                
                <!-- Body Kartu -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="space-y-4 mb-6 flex-1">
                        <!-- Info Pembina -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Pembina Ekskul</p>
                                <p class="text-xs font-bold text-navy-dark mt-0.5">{{ $ekskul->coach->display_name ?? $ekskul->coach->email }}</p>
                            </div>
                        </div>
                        
                        <!-- Info Jadwal -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Jadwal Latihan</p>
                                <p class="text-xs font-bold text-navy-dark mt-0.5">{{ $ekskul->schedule }}</p>
                            </div>
                        </div>

                        <!-- Info Biaya -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Iuran per Bulan</p>
                                <p class="text-sm font-bold text-navy-base mt-0.5">
                                    @if($ekskul->fee > 0)
                                        Rp {{ number_format($ekskul->fee, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600">Gratis (Bebas Biaya)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Berdasarkan Status -->
                    <div class="pt-4 border-t border-navy-light/20 mt-auto">
                        @if(!$status || $status === 'rejected')
                            <form action="{{ route('extracurricular-registrations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="extracurricular_id" value="{{ $ekskul->id }}">
                                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-cyan-400 to-teal-400 hover:brightness-110 text-slate-950 font-bold text-xs rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.35)] active:scale-95 transition-all">
                                    Daftar Ekstrakurikuler
                                </button>
                            </form>
                            @if($status === 'rejected')
                                <p class="text-center text-[11px] font-bold text-rose-500 mt-2">Pendaftaran sebelumnya ditolak admin.</p>
                            @endif
                        
                        @elseif($status === 'pending')
                            <div class="w-full py-2.5 bg-amber-50 text-amber-700 border border-amber-200/60 rounded-xl text-xs font-bold text-center tracking-wider uppercase flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                                Menunggu Persetujuan
                            </div>
                        
                        @elseif($status === 'approved')
                            <!-- Kartu Penilaian Siswa jika sudah dinilai per Tahun Ajaran & Semester -->
                            @if($registration && $registration->grades && $registration->grades->count() > 0)
                                <div class="mb-4 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-muted uppercase tracking-wider">Nilai Capaian Ekskul:</span>
                                    </div>
                                    @foreach($registration->grades->groupBy('academic_year_id') as $yearId => $gradesInYear)
                                        @php
                                            $yearName = $gradesInYear->first()->academicYear->year_name ?? 'Tahun Ajaran';
                                            $ganjil = $gradesInYear->firstWhere('semester', 'ganjil');
                                            $genap = $gradesInYear->firstWhere('semester', 'genap');
                                        @endphp
                                        <div class="p-3 rounded-xl bg-navy-light/10 border border-navy-light/30">
                                            <div class="font-bold text-navy-dark text-[11px] mb-2 flex items-center justify-between">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    T.A. {{ $yearName }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 text-xs">
                                                <!-- Semester Ganjil -->
                                                <div class="bg-white/80 p-2.5 rounded-lg border border-navy-light/20 flex flex-col justify-between">
                                                    <div>
                                                        <span class="text-[9px] font-bold text-gray-muted uppercase tracking-wider block">Sem. Ganjil</span>
                                                        @if($ganjil && $ganjil->grade)
                                                            <div class="flex items-center gap-1 mt-1">
                                                                <span class="px-2 py-0.5 rounded bg-navy-base text-white-off font-bold text-xs">
                                                                    {{ $ganjil->grade }}
                                                                </span>
                                                                @if($ganjil->score !== null)
                                                                    <span class="text-[11px] font-bold text-navy-dark">({{ $ganjil->score }})</span>
                                                                @endif
                                                            </div>
                                                            @if($ganjil->notes)
                                                                <p class="text-[10px] text-navy-dark/80 italic mt-1.5 leading-snug" title="{{ $ganjil->notes }}">"{{ $ganjil->notes }}"</p>
                                                            @endif
                                                        @else
                                                            <span class="text-[10px] text-gray-400 italic block mt-1">Belum dinilai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Semester Genap -->
                                                <div class="bg-white/80 p-2.5 rounded-lg border border-navy-light/20 flex flex-col justify-between">
                                                    <div>
                                                        <span class="text-[9px] font-bold text-gray-muted uppercase tracking-wider block">Sem. Genap</span>
                                                        @if($genap && $genap->grade)
                                                            <div class="flex items-center gap-1 mt-1">
                                                                <span class="px-2 py-0.5 rounded bg-navy-base text-white-off font-bold text-xs">
                                                                    {{ $genap->grade }}
                                                                </span>
                                                                @if($genap->score !== null)
                                                                    <span class="text-[11px] font-bold text-navy-dark">({{ $genap->score }})</span>
                                                                @endif
                                                            </div>
                                                            @if($genap->notes)
                                                                <p class="text-[10px] text-navy-dark/80 italic mt-1.5 leading-snug" title="{{ $genap->notes }}">"{{ $genap->notes }}"</p>
                                                            @endif
                                                        @else
                                                            <span class="text-[10px] text-gray-400 italic block mt-1">Belum dinilai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <div class="flex-1 py-2.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 rounded-xl text-xs font-bold uppercase tracking-wider text-center flex items-center justify-center gap-1.5 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Anggota Aktif
                                </div>
                                
                                <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.remove('hidden')" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-transparent rounded-xl text-xs font-bold active:scale-95 transition-all duration-200" title="Keluar dari Ekskul">
                                    Keluar
                                </button>
                            </div>

                            <!-- Modal Confirm Keluar Ekskul -->
                            <div id="unenrollModal-{{ $ekskul->id }}" class="hidden fixed inset-0 z-[9999] bg-[#070b14]/80 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
                                <div class="glow-card w-full max-w-md rounded-2xl border border-rose-500/30 overflow-hidden text-center relative p-6 sm:p-7 shadow-[0_0_50px_rgba(0,0,0,0.8),0_0_30px_rgba(244,63,94,0.18)] animate-[scale-in_0.2s_ease-out]">
                                    <!-- Ornamen Glow Merah -->
                                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

                                    <div class="relative z-10">
                                        <div class="w-14 h-14 bg-rose-500/15 border border-rose-500/40 text-rose-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_0_20px_rgba(244,63,94,0.3)]">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        </div>

                                        <h3 class="font-extrabold text-lg sm:text-xl text-white font-heading tracking-wide mb-2">Keluar Ekstrakurikuler?</h3>
                                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6 font-medium">
                                            Apakah Anda yakin ingin berhenti mengikuti ekstrakurikuler <span class="font-bold text-cyan-300">{{ $ekskul->name }}</span>? Riwayat pembayaran Anda akan tetap tersimpan aman.
                                        </p>
                                        
                                        <form action="{{ route('extracurricular-registrations.destroy', $ekskul->id) }}" method="POST" class="flex flex-col-reverse sm:flex-row gap-3 justify-center items-center">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.add('hidden')" class="w-full sm:w-auto px-6 py-2.5 bg-[#070b14] hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-cyan-500/20 hover:border-cyan-400/40 transition-all active:scale-95 shadow-sm">
                                                Batal
                                            </button>
                                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-extrabold text-xs rounded-xl shadow-[0_0_20px_rgba(244,63,94,0.4)] hover:shadow-[0_0_30px_rgba(244,63,94,0.6)] active:scale-95 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                <span>Ya, Keluar</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-navy-light/30 shadow-sm">
                <div class="w-14 h-14 bg-white-off text-navy-light rounded-full flex items-center justify-center mx-auto mb-3 border border-navy-light/20">
                    <svg class="w-7 h-7 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Ekstrakurikuler</h3>
                <p class="text-xs text-gray-muted">Saat ini belum terdapat daftar ekstrakurikuler yang tersedia.</p>
            </div>
        @endforelse
    </div>
@endsection
