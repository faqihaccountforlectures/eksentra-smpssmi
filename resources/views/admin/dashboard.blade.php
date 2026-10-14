{{-- Menggunakan cetakan layout utama (app.blade.php) yang memuat Sidebar dan Topbar --}}
@extends('layouts.app')

{{-- Mendefinisikan judul halaman pada tag <title> dan Header Topbar --}}
@section('title', 'Dasbor Administrator')
@section('header', 'Dasbor Administrator')

{{-- Bagian Konten Utama Dasbor --}}
@section('content')
    
    <!-- 1. KARTU SAMBUTAN HERO: Desain Modern, Padat, Glow in the dark -->
    <div class="glow-card rounded-xl p-4 sm:p-5 text-white mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden border border-cyan-500/25 group">
        <!-- Ornamen Cahaya Neon -->
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-colors duration-500 pointer-events-none"></div>
        <div class="absolute right-6 bottom-0 opacity-5 pointer-events-none">
            <svg class="w-36 h-36 text-cyan-400 transform translate-y-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-950/80 border border-cyan-400/30 text-cyan-300 text-[10px] font-mono font-bold tracking-wider mb-2 shadow-[0_0_10px_rgba(6,182,212,0.2)]">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="uppercase">PORTAL ADMIN</span>
            </div>
            <h2 class="text-lg sm:text-xl font-black text-white font-heading tracking-tight">Selamat Datang di Portal Eksentra</h2>
            <p class="text-xs text-slate-300/90 mt-0.5">
                Masuk sebagai <span class="font-bold uppercase text-cyan-300 px-1.5 py-0.5 bg-cyan-950/60 rounded border border-cyan-400/30 text-[10px] font-mono">{{ Auth::user()->role }}</span>. Ringkasan operasional dan aktivitas ekstrakurikuler terkini.
            </p>
        </div>

        <!-- Tanggal & Jam Sistem -->
        <div class="relative z-10 flex items-center gap-2 bg-[#070b14]/60 border border-cyan-500/20 px-3 py-1.5 rounded-lg text-slate-300 text-xs shrink-0 font-mono">
            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- 2. KARTU RINGKASAN KEUANGAN: Padat & Bercahaya Neon -->
    <div class="glow-card rounded-xl p-4 text-white mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 relative overflow-hidden group hover:border-cyan-400/40 transition-all border border-cyan-500/25 bg-gradient-to-r from-cyan-950/30 via-slate-900/60 to-[#0d1527]">
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-cyan-400 text-[10px] font-bold uppercase tracking-widest font-mono">
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Total Pemasukan Ekstrakurikuler Bulan Ini</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading mt-1 text-white tracking-tight drop-shadow-[0_0_15px_rgba(6,182,212,0.35)]">
                Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}
            </p>
        </div>

        <a href="{{ route('admin.payments.index') }}" class="relative z-10 text-xs font-bold px-3.5 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_rgba(6,182,212,0.2)] hover:shadow-[0_0_18px_rgba(6,182,212,0.35)] transition-all flex items-center gap-1.5 self-start sm:self-auto">
            <span>Rincian Keuangan</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- 3. GRID STATISTIK UTAMA (4 Kartu Padat & Rapi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-4">
        
        <!-- Kartu 1: Total Siswa -->
        <div class="glow-card p-3.5 sm:p-4 rounded-xl border border-cyan-500/20 flex items-center justify-between group hover:-translate-y-0.5 hover:border-cyan-400/40 hover:shadow-[0_0_20px_rgba(6,182,212,0.18)] transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Total Siswa Aktif</p>
                <h3 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">{{ $totalSiswa }}</h3>
                <a href="{{ route('students.index') }}" class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Lihat Siswa</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/25 group-hover:scale-105 group-hover:shadow-[0_0_12px_rgba(6,182,212,0.3)] transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
            </div>
        </div>
        
        <!-- Kartu 2: Total Coach -->
        <div class="glow-card p-3.5 sm:p-4 rounded-xl border border-cyan-500/20 flex items-center justify-between group hover:-translate-y-0.5 hover:border-cyan-400/40 hover:shadow-[0_0_20px_rgba(6,182,212,0.18)] transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Total Coach</p>
                <h3 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">{{ $totalCoach }}</h3>
                <a href="{{ route('coaches.index') }}" class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Lihat Coach</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center border border-teal-500/25 group-hover:scale-105 group-hover:shadow-[0_0_12px_rgba(20,184,166,0.3)] transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Pendaftaran Menunggu -->
        <div class="glow-card p-3.5 sm:p-4 rounded-xl border border-amber-500/30 flex items-center justify-between group hover:-translate-y-0.5 hover:border-amber-400/50 hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-amber-300/90 uppercase tracking-wider font-mono">Pendaftaran Baru</p>
                <h3 class="text-xl sm:text-2xl font-black text-amber-300 font-heading mt-0.5">
                    {{ $pendingRegistrations }} <span class="text-[10px] font-medium text-slate-400 font-mono">antrean</span>
                </h3>
                <a href="{{ route('extracurricular-registrations.index') }}" class="text-[11px] font-bold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Verifikasi</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/30 group-hover:scale-105 group-hover:shadow-[0_0_12px_rgba(245,158,11,0.35)] transition-all shrink-0 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                @if($pendingRegistrations > 0)
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Kartu 4: Verifikasi Pembayaran -->
        <div class="glow-card p-3.5 sm:p-4 rounded-xl border border-rose-500/30 flex items-center justify-between group hover:-translate-y-0.5 hover:border-rose-400/50 hover:shadow-[0_0_20px_rgba(244,63,94,0.2)] transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-rose-300/90 uppercase tracking-wider font-mono">Verifikasi Iuran</p>
                <h3 class="text-xl sm:text-2xl font-black text-rose-300 font-heading mt-0.5">
                    {{ $pendingPayments }} <span class="text-[10px] font-medium text-slate-400 font-mono">antrean</span>
                </h3>
                <a href="{{ route('admin.payments.index', ['status' => 'verifikasi']) }}" class="text-[11px] font-bold text-rose-400 hover:text-rose-300 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Validasi</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/30 group-hover:scale-105 group-hover:shadow-[0_0_12px_rgba(244,63,94,0.35)] transition-all shrink-0 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @if($pendingPayments > 0)
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-400"></span>
                    </span>
                @endif
            </div>
        </div>
        
    </div>

    <!-- 4. AREA ANALITIK GRAFIK & TRANSAKSI TERBARU (Layout Rapi 2 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- Visualisasi Data: Grafik Bar Neon -->
        <div class="lg:col-span-2 glow-card p-4 sm:p-5 rounded-xl border border-cyan-500/20 flex flex-col group hover:border-cyan-400/30 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-bold text-white font-heading tracking-wide">Statistik Anggota Ekstrakurikuler</h3>
                    <p class="text-[11px] text-slate-400">Perbandingan jumlah anggota terdaftar aktif per cabang kegiatan.</p>
                </div>
                <span class="px-2 py-0.5 rounded bg-cyan-500/10 border border-cyan-400/30 text-[9px] font-mono text-cyan-300 font-bold uppercase tracking-wider">
                    Aktif
                </span>
            </div>
            <div class="flex-1 w-full relative min-h-[260px]">
                <canvas id="ekskulChart" class="absolute inset-0 w-full h-full"></canvas>
            </div>
        </div>

        <!-- Riwayat Transaksi Terbaru -->
        <div class="glow-card p-4 sm:p-5 rounded-xl border border-cyan-500/20 flex flex-col group hover:border-cyan-400/30 transition-all">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-sm font-bold text-white font-heading tracking-wide">Transaksi Terbaru</h3>
                    <p class="text-[11px] text-slate-400">Setoran pembayaran terkini</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-[10px] font-bold px-2.5 py-1 bg-cyan-500/10 text-cyan-300 rounded-lg hover:bg-cyan-500/20 transition-all border border-cyan-500/25">Semua</a>
            </div>
            
            <div class="space-y-2 overflow-y-auto max-h-[300px] thin-scrollbar pr-1">
                @forelse($recentPayments as $payment)
                    <!-- Baris Item Transaksi -->
                    <div class="flex items-center gap-3 p-2 rounded-lg border border-slate-800/60 bg-[#070b14]/50 hover:bg-cyan-500/5 hover:border-cyan-500/20 transition-all duration-200">
                        <!-- Avatar Inisial -->
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-cyan-900/60 to-slate-800 text-cyan-300 flex items-center justify-center shrink-0 border border-cyan-500/30 font-bold text-xs font-heading">
                            <span>{{ substr($payment->student->studentProfile->full_name ?? 'S', 0, 1) }}</span>
                        </div>
                        
                        <!-- Detail Transaksi -->
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $payment->student->studentProfile->full_name ?? $payment->student->email }}</p>
                            <p class="text-[9px] font-mono text-slate-400 truncate mt-0.5">{{ $payment->extracurricular->name }} • {{ $payment->month }}</p>
                        </div>
                        
                        <!-- Nominal & Status -->
                        <div class="text-right shrink-0">
                            <p class="text-xs font-bold text-cyan-300 font-mono">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            @if($payment->payment_status === 'pending')
                                <span class="inline-block mt-0.5 bg-amber-950/70 text-amber-300 px-1.5 py-0.2 rounded text-[9px] font-bold border border-amber-400/40">Menunggu</span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="inline-block mt-0.5 bg-emerald-950/70 text-emerald-300 px-1.5 py-0.2 rounded text-[9px] font-bold border border-emerald-400/40">Lunas</span>
                            @else
                                <span class="inline-block mt-0.5 bg-rose-950/70 text-rose-300 px-1.5 py-0.2 rounded text-[9px] font-bold border border-rose-400/40">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <div class="w-10 h-10 bg-cyan-500/10 text-cyan-400 rounded-full flex items-center justify-center mx-auto mb-2 border border-cyan-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-xs text-slate-400">Belum ada transaksi pembayaran tercatat.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>

    <!-- SCRIPT CHART.JS DENGAN TEMA NEON GLOW -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('ekskulChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            
            // Gradasi warna bar neon cyan ke teal
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(6, 182, 212, 0.9)');
            gradient.addColorStop(1, 'rgba(20, 184, 166, 0.25)');

            const ekskulChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Anggota',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: gradient,
                        borderColor: '#22d3ee',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        barPercentage: 0.45
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#94a3b8',
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11,
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: 'rgba(56, 189, 248, 0.08)'
                            },
                            border: { display: false }
                        },
                        x: {
                            ticks: {
                                color: '#38bdf8',
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11,
                                    weight: '600'
                                }
                            },
                            grid: { display: false },
                            border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(7, 11, 20, 0.95)',
                            titleColor: '#22d3ee',
                            bodyColor: '#f1f5f9',
                            borderColor: 'rgba(34, 211, 238, 0.4)',
                            borderWidth: 1,
                            padding: 10,
                            titleFont: { family: "'Inter', sans-serif", size: 12, weight: 'bold' },
                            bodyFont: { family: "'Inter', sans-serif", size: 11 },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>
@endsection


