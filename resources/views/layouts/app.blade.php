<!DOCTYPE html>
<html lang="id" class="scroll-smooth" style="color-scheme: light;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eksentra') — Portal Ekstrakurikuler SMP Science Mutiara Insani</title>
    
    <!-- ANTI-FLICKER SIDEBAR: Baca status collapsed sebelum halaman dirender agar tidak berkedip/flicker -->
    <script>
        (function() {
            try {
                if (window.innerWidth >= 768 && localStorage.getItem('sidebar_desktop_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="portal-light-theme bg-slate-100 text-slate-800 antialiased font-sans overflow-hidden relative">

    <!-- CANVAS PARTICLES: Partikel biru tua subtle bergerak tenang di latar terang -->
    <canvas id="glow-canvas" class="fixed inset-0 pointer-events-none z-0 opacity-40"></canvas>

    <!-- FLOATING SOFT ORBS: Efek cahaya biru lembut di background terang -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-10 left-1/4 w-[480px] h-[480px] bg-blue-200/30 rounded-full blur-[120px] animate-float"></div>
        <div class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-indigo-200/25 rounded-full blur-[140px] animate-float-delay"></div>
    </div>

    <!-- FUNGSI KODE: Latar belakang redup (backdrop overlay) saat sidebar mobile dibuka -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

    <div class="flex h-screen overflow-hidden relative z-10">
        
        <!-- FUNGSI KODE: Sidebar navigasi utama (Light theme, putih bersih dengan aksen biru tua) -->
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white flex flex-col transform -translate-x-full md:static md:translate-x-0 shrink-0 shadow-none border-r border-slate-200">
            <!-- Logo area & Identitas Brand EKSENTRA -->
            <div class="h-16 md:h-18 flex items-center justify-between px-5 bg-white border-b border-slate-200 shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-800 to-blue-600 flex items-center justify-center shadow-md">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Eksentra" class="w-6 h-6 object-contain">
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-sm text-blue-900 font-heading tracking-wide">EKSENTRA</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        </div>
                        <p class="text-[8px] text-blue-500 font-bold uppercase tracking-wider">SMP Science Mutiara Insani</p>
                    </div>
                </a>

                <!-- Tombol Tutup (X) khusus layar mobile -->
                <button type="button" onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-blue-700 hover:bg-blue-50 transition-colors" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Menu Navigasi Padat & Rapi -->
            <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto thin-scrollbar-light">
                
                <a href="/dashboard" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->is('dashboard') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->is('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dasbor
                </a>
                
                @if(Auth::user()->role === 'admin')
                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Akademik Dasar</p>

                <!-- Menu Tahun Ajaran -->
                <a href="{{ route('academic-years.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('academic-years.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('academic-years.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ajaran
                </a>
                
                <!-- Menu Kelas -->
                <a href="{{ route('classes.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('classes.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('classes.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelas
                </a>

                <!-- Menu Pembagian Kelas -->
                <a href="{{ route('class-enrollments.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('class-enrollments.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('class-enrollments.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pembagian Kelas
                </a>

                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Pengguna</p>

                <!-- Menu Data Pembina / Coach -->
                <a href="{{ route('coaches.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('coaches.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('coaches.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Data Pembina
                </a>
                
                <!-- Menu Data Siswa -->
                <a href="{{ route('students.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('students.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('students.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                    Data Siswa
                </a>

                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Ekstrakurikuler & Keuangan</p>

                <!-- Navigasi Menu Ekstrakurikuler -->
                <a href="{{ route('extracurriculars.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('extracurriculars.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('extracurriculars.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Data Ekstrakurikuler
                </a>

                <!-- Navigasi Menu Pendaftaran Ekstrakurikuler -->
                <a href="{{ route('extracurricular-registrations.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('extracurricular-registrations.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <div class="flex items-center gap-2.5 truncate">
                        <svg class="w-4 h-4 {{ request()->routeIs('extracurricular-registrations.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="truncate">Pendaftaran Ekskul</span>
                    </div>
                    @if(($pendingRegistrationsCount ?? 0) > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-mono font-black bg-amber-400 text-slate-900 shrink-0">
                            {{ $pendingRegistrationsCount }}
                        </span>
                    @endif
                </a>

                <!-- Navigasi Menu Verifikasi Pembayaran -->
                <a href="{{ route('admin.payments.index') }}" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('admin.payments.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <div class="flex items-center gap-2.5 truncate">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="truncate">Verifikasi Pembayaran</span>
                    </div>
                    @if(($pendingPaymentsCount ?? 0) > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-mono font-black bg-amber-400 text-slate-900 shrink-0">
                            {{ $pendingPaymentsCount }}
                        </span>
                    @endif
                </a>

                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Pengaturan Akun</p>

                <!-- Navigasi Menu Profil Admin -->
                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('admin.profile.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.profile.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil & Akun Admin
                </a>

                @elseif(Auth::user()->role === 'coach')
                <!-- MENU KHUSUS COACH / PEMBINA -->
                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Ekstrakurikuler</p>

                <a href="{{ route('coach.extracurriculars.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('coach.extracurriculars.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('coach.extracurriculars.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Ekskul Binaan & Penilaian
                </a>

                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Akun Saya</p>

                <a href="{{ route('coach.profile.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('coach.profile.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('coach.profile.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>

                @elseif(Auth::user()->role === 'siswa')
                <!-- MENU KHUSUS SISWA -->
                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Aktivitas Siswa</p>

                <a href="{{ route('siswa.extracurriculars.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('siswa.extracurriculars.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('siswa.extracurriculars.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Katalog & Ekskul Saya
                </a>
                
                <a href="{{ route('siswa.payments.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('siswa.payments.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('siswa.payments.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Pembayaran
                </a>

                <p class="px-3 pt-3 pb-1 text-[9px] font-bold text-blue-400 uppercase tracking-widest font-mono">Akun Saya</p>

                <a href="{{ route('siswa.profile.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition-colors duration-150 {{ request()->routeIs('siswa.profile.*') ? 'bg-blue-800 text-white font-semibold shadow-none' : 'text-slate-500 hover:text-blue-800 hover:bg-blue-50 font-medium group' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('siswa.profile.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-700 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>
                @endif
            </nav>
        </aside>

        <!-- Area Kanan (Topbar & Konten Dinamis) -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            
            <!-- FUNGSI KODE: Topbar atas berdesain bersih & modern, background putih dengan aksen biru tua -->
            <header class="h-14 md:h-16 bg-white border-b border-blue-100 flex items-center justify-between px-4 md:px-6 z-10 shrink-0 shadow-sm">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Tombol Hamburger untuk menyembunyikan/memunculkan sidebar -->
                    <button type="button" onclick="toggleSidebar()" class="p-1.5 rounded-lg text-slate-500 hover:text-blue-800 hover:bg-blue-50 border border-slate-200 transition-all shrink-0 active:scale-95 outline-none focus:outline-none" title="Sembunyikan / Tampilkan Menu" aria-label="Toggle Menu Navigasi">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h1 class="text-sm md:text-base font-extrabold text-blue-900 font-heading truncate tracking-wide flex items-center gap-2">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>
                
                @php
                    $profileRoute = match(Auth::user()->role) {
                        'admin'   => route('admin.profile.index'),
                        'coach'   => route('coach.profile.index'),
                        'siswa'   => route('siswa.profile.index'),
                        default   => '#',
                    };

                    $roleBadgeClass = match(Auth::user()->role) {
                        'admin'   => 'bg-purple-100 text-purple-700 border-purple-200',
                        'coach'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'siswa'   => 'bg-blue-100 text-blue-700 border-blue-200',
                        default   => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                @endphp

                <!-- Bagian Informasi Profil Pengguna & Notifikasi -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    
                    @if(Auth::user()->role === 'admin')
                    @php
                        $notifTargetUrl = ($pendingRegistrationsCount ?? 0) > 0 
                            ? route('extracurricular-registrations.index') 
                            : route('admin.payments.index');
                        $notifTooltip = ($totalPendingCount ?? 0) > 0 
                            ? $totalPendingCount . ' antrean butuh diverifikasi (Klik untuk membuka)' 
                            : 'Tidak ada antrean verifikasi';
                    @endphp
                    <!-- FUNGSI KODE: Tombol Lonceng Penanda Verifikasi Langsung (Tanpa Popover) -->
                    <a href="{{ $notifTargetUrl }}" class="relative p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-800 hover:border-blue-300 hover:bg-blue-50 transition-all outline-none focus:outline-none active:scale-95" title="{{ $notifTooltip }}" aria-label="{{ $notifTooltip }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ ($totalPendingCount ?? 0) > 0 ? 'text-amber-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        
                        @if(($totalPendingCount ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 text-white font-black text-[9px] items-center justify-center font-mono">
                                    {{ $totalPendingCount > 9 ? '9+' : $totalPendingCount }}
                                </span>
                            </span>
                        @endif
                    </a>

                    <!-- Pembatas -->
                    <div class="h-5 w-px bg-slate-200"></div>
                    @endif

                    <!-- Tautan Profil -->
                    <a href="{{ $profileRoute }}" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-blue-50 transition-all group outline-none focus:outline-none" title="Buka Profil Saya">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-700 group-hover:text-blue-800 transition-colors truncate max-w-[130px] md:max-w-[180px]">
                                {{ Auth::user()->display_name }}
                            </p>
                            <span class="inline-block text-[8px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 rounded border mt-0.5 {{ $roleBadgeClass }}">
                                {{ Auth::user()->role }}
                            </span>
                        </div>

                        <!-- Avatar Inisial dengan gradient biru tua -->
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-gradient-to-tr from-blue-800 to-blue-600 text-white flex items-center justify-center font-black text-xs font-heading shadow-md border-2 border-blue-200 shrink-0">
                            {{ Auth::user()->initial }}
                        </div>
                    </a>

                    <!-- Pembatas -->
                    <div class="h-5 w-px bg-slate-200"></div>

                    <!-- Tombol Logout -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1.5 sm:p-2 bg-white border border-slate-200 text-slate-400 rounded-lg hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all outline-none focus:outline-none" title="Keluar dari Sistem">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Area Konten Dinamis (Tampilan Rapi & Padat) -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-3.5 sm:p-5 md:p-6 thin-scrollbar-light relative z-10">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // ==============================================================
        // 1. SIDEBAR TOGGLE ENGINE (Zero flicker on page load)
        // ==============================================================
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar) return;

            // Aktifkan transisi hanya saat terjadi interaksi user
            sidebar.classList.add('sidebar-ready');

            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    if (backdrop) backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    if (backdrop) backdrop.classList.add('hidden');
                }
            } else {
                const isCurrentlyCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
                if (isCurrentlyCollapsed) {
                    document.documentElement.classList.remove('sidebar-collapsed');
                    localStorage.setItem('sidebar_desktop_collapsed', 'false');
                } else {
                    document.documentElement.classList.add('sidebar-collapsed');
                    localStorage.setItem('sidebar_desktop_collapsed', 'true');
                }
            }
        }

        // Aktifkan transisi halus setelah render awal selesai sepenuhnya
        window.addEventListener('load', function() {
            setTimeout(function() {
                const sidebar = document.getElementById('mobile-sidebar');
                if (sidebar) sidebar.classList.add('sidebar-ready');
            }, 100);
        });

        // ==============================================================
        // 2. CANVAS PARTICLES: Biru tua subtle di background terang
        // ==============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('glow-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', function() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }, { passive: true });

            // Jumlah partikel pas & tenang agar tidak membebani performa kerja
            const count = Math.min(Math.floor(window.innerWidth / 50), 22);
            const particles = [];

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2 + 1,
                    vx: (Math.random() - 0.5) * 0.22,
                    vy: (Math.random() - 0.5) * 0.22,
                    alpha: Math.random() * 0.25 + 0.08,
                    color: Math.random() > 0.5 ? 'rgba(30, 64, 175, ' : 'rgba(37, 99, 235, '
                });
            }

            function renderParticles() {
                ctx.clearRect(0, 0, width, height);

                // Garis jala halus antar partikel berdekatan
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 120) {
                            ctx.strokeStyle = `rgba(30, 64, 175, ${0.06 * (1 - dist / 120)})`;
                            ctx.lineWidth = 0.6;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }

                // Gambar partikel biru tua halus
                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0) p.x = width;
                    if (p.x > width) p.x = 0;
                    if (p.y < 0) p.y = height;
                    if (p.y > height) p.y = 0;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color + p.alpha + ')';
                    ctx.fill();
                });

                requestAnimationFrame(renderParticles);
            }

            renderParticles();
        });

        // Auto-teleport modals to document.body so they span the entire screen without being trapped inside table/card containers
        document.addEventListener('DOMContentLoaded', function() {
            const modalSelectors = [
                'div[id^="deleteModal-"]',
                'div[id^="removeModal-"]',
                'div[id^="rejectModal-"]',
                'div[id^="unenrollModal-"]',
                '#coachImportModal',
                '#studentImportModal',
                '#gradeModal'
            ];

            const modals = document.querySelectorAll(modalSelectors.join(', '));
            modals.forEach(function(modal) {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                // Close on backdrop click (click outside the modal content card)
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Close active modal on Escape key press
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modals.forEach(function(modal) {
                        if (!modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

