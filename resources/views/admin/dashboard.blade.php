{{-- Menggunakan cetakan layout utama (app.blade.php) yang memuat Sidebar dan Topbar --}}
@extends('layouts.app')

{{-- Mendefinisikan judul halaman pada tag <title> dan Header Topbar --}}
@section('title', 'Dasbor Administrator')
@section('header', 'Dasbor Administrator')

{{-- Bagian Konten Utama Dasbor --}}
@section('content')
    
    <!-- 1. KARTU SAMBUTAN HERO: Desain Modern, Berwibawa dengan Gradasi Biru Tua -->
    <div class="rounded-2xl p-5 sm:p-6 text-white mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-950 shadow-md group">
        <!-- Ornamen Halus -->
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-6 bottom-0 opacity-10 pointer-events-none">
            <svg class="w-36 h-36 text-white transform translate-y-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-900/80 border border-blue-700/60 text-blue-200 text-[10px] font-mono font-bold tracking-wider mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="uppercase">PORTAL ADMIN</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-heading tracking-tight">Selamat Datang di Portal Eksentra</h2>
            <p class="text-xs text-blue-100 mt-1.5">
                Masuk sebagai <span class="font-bold uppercase text-white px-2 py-0.5 bg-blue-800/80 rounded border border-blue-600/50 text-[10px] font-mono">{{ Auth::user()->role }}</span>. Ringkasan operasional dan aktivitas ekstrakurikuler terkini.
            </p>
        </div>

        <!-- Tanggal & Jam Sistem -->
        <div class="relative z-10 flex items-center gap-2 bg-blue-900/80 border border-blue-700/60 px-3.5 py-2 rounded-xl text-white text-xs shrink-0 font-mono">
            <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-white font-semibold">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- 2. KARTU RINGKASAN KEUANGAN: Modern & Elegan -->
    <div class="bg-white rounded-2xl p-5 mb-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden group hover:border-blue-300 transition-all border border-blue-100 shadow-sm">
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-blue-800 text-[11px] font-bold uppercase tracking-wider font-mono">
                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Total Pemasukan Ekstrakurikuler Bulan Ini</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading mt-1 text-slate-900 tracking-tight">
                Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}
            </p>
        </div>

        <a href="{{ route('admin.payments.index') }}" class="relative z-10 text-xs font-bold px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2 self-start sm:self-auto active:scale-95">
            <span>Rincian Keuangan</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- 3. GRID STATISTIK UTAMA (4 Kartu Bersih & Rapi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        
        <!-- Kartu 1: Total Siswa -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-mono">Total Siswa Aktif</p>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <h3 class="text-2xl font-black text-slate-900 font-heading">{{ $totalSiswa }}</h3>
                    <a href="{{ route('students.index', ['status' => 'lulus']) }}" class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md hover:bg-emerald-100 transition-colors" title="Lihat Alumni">
                        +{{ $totalAlumni }} Alumni
                    </a>
                </div>
                <a href="{{ route('students.index', ['status' => 'aktif']) }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Lihat Siswa</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center border border-blue-200 group-hover:scale-105 transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
            </div>
        </div>
        
        <!-- Kartu 2: Total Coach -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-mono">Total Coach</p>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-0.5">{{ $totalCoach }}</h3>
                <a href="{{ route('coaches.index') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Lihat Coach</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-800 flex items-center justify-center border border-indigo-200 group-hover:scale-105 transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Pendaftaran Menunggu -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-amber-200 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider font-mono">Pendaftaran Baru</p>
                <h3 class="text-2xl font-black text-amber-800 font-heading mt-0.5">
                    {{ $pendingRegistrations }} <span class="text-xs font-medium text-slate-500 font-mono">antrean</span>
                </h3>
                <a href="{{ route('extracurricular-registrations.index') }}" class="text-[11px] font-bold text-amber-700 hover:text-amber-900 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Verifikasi</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-200 group-hover:scale-105 transition-all shrink-0 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                @if($pendingRegistrations > 0)
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Kartu 4: Verifikasi Pembayaran -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-rose-200 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:border-rose-300 hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold text-rose-700 uppercase tracking-wider font-mono">Verifikasi Iuran</p>
                <h3 class="text-2xl font-black text-rose-800 font-heading mt-0.5">
                    {{ $pendingPayments }} <span class="text-xs font-medium text-slate-500 font-mono">antrean</span>
                </h3>
                <a href="{{ route('admin.payments.index', ['status' => 'verifikasi']) }}" class="text-[11px] font-bold text-rose-700 hover:text-rose-900 inline-flex items-center gap-1 mt-1.5 transition-colors">
                    <span>Validasi</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center border border-rose-200 group-hover:scale-105 transition-all shrink-0 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @if($pendingPayments > 0)
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                    </span>
                @endif
            </div>
        </div>
        
    </div>

    <!-- 4. AREA ANALITIK GRAFIK & TRANSAKSI TERBARU (Layout Rapi 2 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Visualisasi Data: Grafik Bar Biru Modern -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col group hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading tracking-wide">Statistik Anggota Ekstrakurikuler</h3>
                    <p class="text-[11px] text-slate-500">Perbandingan jumlah anggota terdaftar aktif per cabang kegiatan.</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-200 text-[10px] font-mono text-blue-800 font-bold uppercase tracking-wider">
                    Aktif
                </span>
            </div>
            <div class="flex-1 w-full relative min-h-[260px]">
                <canvas id="ekskulChart" class="absolute inset-0 w-full h-full"></canvas>
            </div>
        </div>

        <!-- Riwayat Transaksi Terbaru -->
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col group hover:border-blue-300 transition-all">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading tracking-wide">Transaksi Terbaru</h3>
                    <p class="text-[11px] text-slate-500">Setoran pembayaran terkini</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-[10px] font-bold px-3 py-1 bg-blue-50 text-blue-800 rounded-lg hover:bg-blue-100 transition-all border border-blue-200">Semua</a>
            </div>
            
            <div class="space-y-2.5 overflow-y-auto max-h-[300px] thin-scrollbar-light pr-1">
                @forelse($recentPayments as $payment)
                    <!-- Baris Item Transaksi -->
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-blue-50/60 hover:border-blue-200 transition-all duration-200">
                        <!-- Avatar Inisial -->
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center shrink-0 border border-blue-200 font-bold text-xs font-heading">
                            <span>{{ substr($payment->student->studentProfile->full_name ?? 'S', 0, 1) }}</span>
                        </div>
                        
                        <!-- Detail Transaksi -->
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $payment->student->studentProfile->full_name ?? ($payment->student->studentProfile->nis ? 'Siswa NIS ' . $payment->student->studentProfile->nis : 'Siswa') }}</p>
                            <p class="text-[10px] font-mono text-slate-500 truncate mt-0.5">{{ $payment->extracurricular->name }} • {{ $payment->month }}</p>
                        </div>
                        
                        <!-- Nominal & Status -->
                        <div class="text-right shrink-0">
                            <p class="text-xs font-bold text-blue-900 font-mono">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            @if($payment->payment_status === 'pending')
                                <span class="inline-block mt-0.5 bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded text-[9px] font-bold border border-amber-200">Menunggu</span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="inline-block mt-0.5 bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded text-[9px] font-bold border border-emerald-200">Lunas</span>
                            @else
                                <span class="inline-block mt-0.5 bg-rose-100 text-rose-800 px-1.5 py-0.5 rounded text-[9px] font-bold border border-rose-200">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-full flex items-center justify-center mx-auto mb-2 border border-blue-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-xs text-slate-500">Belum ada transaksi pembayaran tercatat.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>

    <!-- SCRIPT CHART.JS DENGAN TEMA BIRU TUA MODERN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('ekskulChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            
            // Gradasi warna bar biru tua ke biru muda
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(30, 58, 138, 0.9)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0.35)');

            const ekskulChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Anggota',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: gradient,
                        borderColor: '#1e40af',
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
                                color: '#64748b',
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11,
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: 'rgba(30, 58, 138, 0.06)'
                            },
                            border: { display: false }
                        },
                        x: {
                            ticks: {
                                color: '#1e3a8a',
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
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#e2e8f0',
                            borderColor: 'rgba(30, 58, 138, 0.3)',
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
