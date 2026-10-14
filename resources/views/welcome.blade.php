<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksentra — Portal Ekstrakurikuler SMP Science Mutiara Insani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Ambient Background Canvas */
        #glow-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Scroll Reveal Transitions */
        .reveal { opacity: 0; transform: translateY(24px); transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .reveal-left { opacity: 0; transform: translateX(-30px); transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal-left.active { opacity: 1; transform: translateX(0); }
        .reveal-right { opacity: 0; transform: translateX(30px); transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal-right.active { opacity: 1; transform: translateX(0); }
        .reveal-scale { opacity: 0; transform: scale(0.92); transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal-scale.active { opacity: 1; transform: scale(1); }

        /* Staggered Children Delay */
        .stagger-items > *:nth-child(1) { transition-delay: 0.04s; }
        .stagger-items > *:nth-child(2) { transition-delay: 0.08s; }
        .stagger-items > *:nth-child(3) { transition-delay: 0.12s; }
        .stagger-items > *:nth-child(4) { transition-delay: 0.16s; }
        .stagger-items > *:nth-child(5) { transition-delay: 0.20s; }
        .stagger-items > *:nth-child(6) { transition-delay: 0.24s; }
        .stagger-items > *:nth-child(7) { transition-delay: 0.28s; }
        .stagger-items > *:nth-child(8) { transition-delay: 0.32s; }

        /* Neon Glow Drop Shadows */
        .neon-shadow-cyan {
            filter: drop-shadow(0 0 25px rgba(6, 182, 212, 0.45));
        }
        .neon-glow-border {
            border: 1px solid rgba(34, 211, 238, 0.3);
            box-shadow: 0 0 18px rgba(6, 182, 212, 0.2), inset 0 0 12px rgba(6, 182, 212, 0.1);
        }

        /* Hilangkan outline dan border putih default browser saat link navbar diklik atau aktif */
        .nav-link,
        .nav-link:focus,
        .nav-link:focus-visible,
        .nav-link:active,
        #mobile-menu a,
        #mobile-menu a:focus,
        #mobile-menu a:focus-visible,
        #mobile-menu a:active,
        #main-nav a,
        #main-nav a:focus,
        #main-nav a:focus-visible,
        #main-nav button:focus,
        #main-nav button:focus-visible {
            outline: none !important;
            border-color: transparent;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="bg-[#070b14] text-slate-100 antialiased font-sans selection:bg-cyan-500 selection:text-black overflow-x-hidden relative">

    <!-- CANVAS PARTICLES: Partikel neon interaktif bergerak pelan di latar belakang -->
    <canvas id="glow-canvas"></canvas>

    <!-- FLOATING GLOW ORBS: Lingkaran cahaya neon yang melayang perlahan -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-10 left-1/4 w-[420px] h-[420px] bg-cyan-500/10 rounded-full blur-[110px] animate-float"></div>
        <div class="absolute top-1/2 -right-20 w-[460px] h-[460px] bg-teal-500/10 rounded-full blur-[130px] animate-float-delay"></div>
        <div class="absolute bottom-20 left-10 w-[380px] h-[380px] bg-purple-500/10 rounded-full blur-[120px] animate-float-slow"></div>
    </div>

    <!-- ============================================================== -->
    <!-- NAVBAR UTAMA: Glow in the dark, Sticky, Login Sejajar di Kanan -->
    <!-- ============================================================== -->
    <nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-[#070b14]/85 backdrop-blur-xl border-b border-cyan-500/20 shadow-[0_4px_30px_rgba(0,0,0,0.7)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-18 items-center">
                
                <!-- Logo & Identitas Brand EKSENTRA -->
                <a href="#hero" class="flex items-center gap-3 group">
                    <div class="relative">
                        <div class="absolute -inset-1 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-400 opacity-60 blur-sm group-hover:opacity-100 transition-opacity"></div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Eksentra" class="relative w-9 h-9 sm:w-10 sm:h-10 object-contain group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-lg sm:text-xl text-white font-heading tracking-wide">EKSENTRA</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        </div>
                        <p class="text-[9px] sm:text-[10px] text-cyan-300/80 font-semibold uppercase tracking-wider hidden sm:block">SMP Science Mutiara Insani</p>
                    </div>
                </a>

                <!-- Nav Menu Tengah (Sesuai Masing-masing Section) -->
                <div class="hidden xl:flex items-center gap-1">
                    <a href="#hero" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-cyan-300 bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Beranda</a>
                    <a href="#tentang" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Tentang</a>
                    <a href="#tujuan" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Tujuan</a>
                    <a href="#keunggulan" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Keunggulan</a>
                    <a href="#galeri" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Galeri</a>
                    <a href="#visi-misi" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Visi & Misi</a>
                    <a href="#profil-lokasi" class="nav-link px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none focus:ring-0 transition-all">Media & Lokasi</a>
                </div>

                <!-- Tombol Login Sejajar di Navbar (Glowing CTA) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="relative group inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm text-slate-950 overflow-hidden transition-all duration-300 active:scale-95 shadow-[0_0_20px_rgba(34,211,238,0.45)] hover:shadow-[0_0_30px_rgba(34,211,238,0.7)] outline-none focus:outline-none">
                        <!-- Background Glow Gradient -->
                        <span class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 group-hover:brightness-110 transition-all"></span>
                        <span class="relative flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            <span>Masuk Portal</span>
                        </span>
                    </a>

                    <!-- Mobile Menu Hamburger -->
                    <button type="button" class="xl:hidden p-2 rounded-lg text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 border border-slate-800 transition-colors outline-none focus:outline-none" onclick="toggleMobileMenu()" aria-label="Buka menu navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="xl:hidden hidden border-t border-cyan-500/20 bg-[#070b14]/95 backdrop-blur-2xl">
            <div class="px-4 py-3 space-y-1">
                <a href="#hero" class="block px-3 py-2 rounded-lg text-xs font-semibold text-cyan-300 bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Beranda</a>
                <a href="#tentang" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Tentang Eksentra</a>
                <a href="#tujuan" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Tujuan Ekskul</a>
                <a href="#keunggulan" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Keunggulan</a>
                <a href="#galeri" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Galeri</a>
                <a href="#visi-misi" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Visi & Misi</a>
                <a href="#profil-lokasi" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 outline-none focus:outline-none" onclick="closeMobileMenu()">Media & Lokasi</a>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER: Di atas Canvas -->
    <main class="relative z-10 pt-16 sm:pt-18">

        <!-- ============================================================== -->
        <!-- 1. HERO SECTION: Nama EKSENTRA jelas di awal beranda -->
        <!-- ============================================================== -->
        <section id="hero" class="scroll-mt-20 py-12 sm:py-16 lg:py-20 flex items-center relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">

                <!-- Badge Selamat Datang -->
                <div class="reveal inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-cyan-950/60 border border-cyan-400/30 text-cyan-300 text-xs font-bold tracking-wider mb-5 shadow-[0_0_15px_rgba(6,182,212,0.25)] backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    <span class="font-mono tracking-widest text-[11px] uppercase">PORTAL RESMI EKSTRAKURIKULER</span>
                </div>

                <!-- Penegasan Nama EKSENTRA di Awal Beranda -->
                <h1 class="reveal text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black font-heading tracking-tight mb-3">
                    <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 neon-shadow-cyan">
                        EKSENTRA
                    </span>
                </h1>

                <!-- Slogan & Subjudul Resmi Dokumen -->
                <p class="reveal text-xl sm:text-2xl md:text-3xl font-extrabold text-white mb-2 font-heading tracking-wide">
                    Satu Portal, <span class="text-cyan-400 glow-text-cyan">Beragam Potensi.</span>
                </p>
                <p class="reveal text-xs sm:text-sm font-semibold text-cyan-300/80 uppercase tracking-widest mb-5 font-mono">
                    Portal Ekstrakurikuler SMP Science Mutiara Insani
                </p>

                <!-- Deskripsi Hero -->
                <p class="reveal text-sm sm:text-base text-slate-300/90 max-w-2xl mx-auto leading-relaxed mb-8">
                    Eksentra merupakan portal terintegrasi yang mendukung pengelolaan kegiatan ekstrakurikuler di SMP Science Mutiara Insani. Melalui Eksentra, pengelolaan administrasi ekstrakurikuler menjadi lebih terstruktur sehingga siswa dapat mengembangkan minat, menemukan bakat, dan menggali potensi diri melalui kegiatan non-akademik.
                </p>

                <!-- CTA Buttons -->
                <div class="reveal flex flex-wrap justify-center items-center gap-3.5">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 hover:brightness-110 text-slate-950 px-6 sm:px-7 py-3 rounded-xl font-extrabold text-sm transition-all shadow-[0_0_25px_rgba(34,211,238,0.5)] hover:-translate-y-0.5 active:scale-95">
                        <span>Masuk ke Portal</span>
                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#tentang" class="inline-flex items-center gap-2 bg-slate-900/80 hover:bg-slate-800 text-slate-200 hover:text-cyan-300 px-6 sm:px-7 py-3 rounded-xl font-bold text-sm border border-cyan-500/20 hover:border-cyan-400/40 transition-all backdrop-blur-md hover:-translate-y-0.5">
                        <span>Tentang Eksentra</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </div>

                <!-- Mini Status Highlights (Kompak & Elegan) -->
                <div class="reveal mt-10 grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-4xl mx-auto">
                    <div class="glow-card p-3.5 rounded-2xl text-center">
                        <div class="text-xs text-cyan-300 font-mono">PENGGUNA</div>
                        <div class="text-sm font-bold text-white mt-0.5">Admin • Pembina • Siswa</div>
                    </div>
                    <div class="glow-card p-3.5 rounded-2xl text-center">
                        <div class="text-xs text-teal-300 font-mono">STATUS DATA</div>
                        <div class="text-sm font-bold text-white mt-0.5">Terpusat & Digital</div>
                    </div>
                    <div class="glow-card p-3.5 rounded-2xl text-center">
                        <div class="text-xs text-purple-300 font-mono">AKREDITASI</div>
                        <div class="text-sm font-bold text-white mt-0.5">Terakreditasi A</div>
                    </div>
                    <div class="glow-card p-3.5 rounded-2xl text-center">
                        <div class="text-xs text-amber-300 font-mono">LOKASI</div>
                        <div class="text-sm font-bold text-white mt-0.5">Purwakarta, Jabar</div>
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 2. TENTANG EKSENTRA (Kompak & Glow) -->
        <!-- ============================================================== -->
        <section id="tentang" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Left: Penjelasan Konsep Eksentra -->
                    <div class="lg:col-span-7 reveal-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <span>Mengenal Eksentra</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white font-heading leading-tight mb-4">
                            Sistem Terpadu Pengelolaan <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300">Kegiatan Ekstrakurikuler</span>
                        </h2>
                        <div class="space-y-3 text-xs sm:text-sm text-slate-300/90 leading-relaxed">
                            <p>
                                <strong class="text-cyan-300 font-semibold">Eksentra</strong> adalah Portal Ekstrakurikuler SMP Science Mutiara Insani yang dirancang untuk mengintegrasikan berbagai proses pengelolaan kegiatan ekstrakurikuler dalam satu sistem terpusat.
                            </p>
                            <p>
                                Portal ini memfasilitasi pengelolaan data ekstrakurikuler, pendaftaran siswa, verifikasi pembayaran, hingga pencatatan nilai kegiatan. Dengan pembagian akses bagi admin, pembina, dan siswa, Eksentra membantu menciptakan pengelolaan ekstrakurikuler yang lebih terorganisasi, efektif, dan mudah diakses.
                            </p>
                            <p class="text-cyan-300/90 font-medium pt-1">
                                ✦ Eksentra hadir sebagai bagian dari upaya mendukung pengembangan minat dan bakat siswa di luar kegiatan akademik.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Mini Grid Peran Pengguna (Kompak) -->
                    <div class="lg:col-span-5 reveal-right">
                        <div class="glow-card p-5 sm:p-6 rounded-3xl space-y-3.5 border border-cyan-500/20">
                            <h3 class="text-sm font-bold text-white font-heading flex items-center justify-between border-b border-slate-800 pb-2.5">
                                <span>3 Hak Akses Utama</span>
                                <span class="text-[11px] text-cyan-400 font-mono">Eksentra System</span>
                            </h3>

                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 flex items-center justify-center shrink-0 text-sm font-bold">1</div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Administrator</h4>
                                    <p class="text-[11px] text-slate-400">Pengelolaan master data, pembagian kelas, persetujuan pendaftaran, dan verifikasi.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/10 border border-teal-500/30 text-teal-300 flex items-center justify-center shrink-0 text-sm font-bold">2</div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Pembina Ekstrakurikuler</h4>
                                    <p class="text-[11px] text-slate-400">Pemantauan anggota aktif, jadwal kegiatan, absensi, serta penilaian berkala.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-center shrink-0 text-sm font-bold">3</div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Siswa / Peserta Didik</h4>
                                    <p class="text-[11px] text-slate-400">Eksplorasi katalog ekskul di dashboard, pendaftaran, konfirmasi pembayaran & riwayat nilai.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 3. TUJUAN PENGEMBANGAN EKSKUL (Kompak 3 Card) -->
        <!-- ============================================================== -->
        <section id="tujuan" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-8 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-2">
                        <span>Tujuan Pengembangan</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                        Tujuan Pengembangan <span class="text-cyan-400">Ekskul</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        Mendukung pengelolaan kegiatan ekstrakurikuler secara terpusat, terstruktur, dan efisien.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 stagger-items">
                    <!-- Tujuan 1 -->
                    <div class="glow-card p-6 rounded-2xl reveal group">
                        <div class="w-11 h-11 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-white font-heading mb-2">Pengelolaan Terpusat</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Proses administrasi dapat dilakukan dengan lebih efisien, informasi kegiatan dapat dikelola secara terorganisasi, dan perkembangan siswa dapat dicatat dengan lebih baik.
                        </p>
                    </div>

                    <!-- Tujuan 2 -->
                    <div class="glow-card p-6 rounded-2xl reveal group">
                        <div class="w-11 h-11 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-white font-heading mb-2">Penelusuran Minat & Bakat</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Menjadi sarana pendukung bagi siswa untuk mengenali minat, menemukan bakat, dan mengembangkan potensi diri melalui kegiatan non-akademik sekolah.
                        </p>
                    </div>

                    <!-- Tujuan 3 -->
                    <div class="glow-card p-6 rounded-2xl reveal group">
                        <div class="w-11 h-11 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-white font-heading mb-2">Kualitas & Evaluasi Terukur</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Pencatatan nilai dan evaluasi oleh pembina ekskul tersimpan terstruktur sehingga mempermudah rekapitulasi prestasi siswa dan pelaporan resmi sekolah.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 4. KEUNGGULAN EKSENTRA (4 Kartu Glow) -->
        <!-- ============================================================== -->
        <section id="keunggulan" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-8 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-2">
                        <span>Poin Keunggulan</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                        Keunggulan <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300">Eksentra</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        Fondasi keunggulan sistem yang dirancang untuk kelancaran kegiatan ekstrakurikuler.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 stagger-items">
                    <!-- Keunggulan 1 -->
                    <div class="glow-card p-5 rounded-2xl reveal group text-left">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-400/40 text-cyan-300 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white font-heading mb-1.5">Pengelolaan Terpusat</h3>
                        <p class="text-xs text-slate-300/80 leading-relaxed">
                            Seluruh kebutuhan administrasi ekstrakurikuler terintegrasi dalam satu portal sehingga data lebih mudah dikelola dan ditelusuri.
                        </p>
                    </div>

                    <!-- Keunggulan 2 -->
                    <div class="glow-card p-5 rounded-2xl reveal group text-left">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/15 border border-teal-400/40 text-teal-300 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white font-heading mb-1.5">Administrasi Terstruktur</h3>
                        <p class="text-xs text-slate-300/80 leading-relaxed">
                            Pengelolaan pendaftaran, verifikasi pembayaran, dan pencatatan nilai dilakukan melalui alur yang lebih terorganisasi.
                        </p>
                    </div>

                    <!-- Keunggulan 3 -->
                    <div class="glow-card p-5 rounded-2xl reveal group text-left">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-400/40 text-purple-300 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white font-heading mb-1.5">Akses Sesuai Peran</h3>
                        <p class="text-xs text-slate-300/80 leading-relaxed">
                            Admin, pembina, dan siswa memperoleh akses fitur sesuai tanggung jawab dan kebutuhan masing-masing.
                        </p>
                    </div>

                    <!-- Keunggulan 4 -->
                    <div class="glow-card p-5 rounded-2xl reveal group text-left">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-400/40 text-amber-300 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white font-heading mb-1.5">Mendukung Potensi Siswa</h3>
                        <p class="text-xs text-slate-300/80 leading-relaxed">
                            Membantu sekolah mengelola kegiatan ekstrakurikuler yang menjadi wadah pengembangan minat, bakat, dan kemampuan siswa.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 5. GALERI KEGIATAN (Placeholder Rapi & Kompak) -->
        <!-- ============================================================== -->
        <section id="galeri" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-8 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-2">
                        <span>Cerita dalam Setiap Kegiatan</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                        Galeri <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300">Kegiatan</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        Jelajahi berbagai momen kegiatan ekstrakurikuler yang menjadi bagian dari perjalanan siswa SMP Science Mutiara Insani.
                    </p>
                </div>

                <!-- 8 Kompak Placeholder Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 stagger-items">
                    @php
                        $galeriItems = [
                            ['label' => 'Sains & Robotik', 'cat' => 'Akademik & Riset'],
                            ['label' => 'Pramuka & Kepemimpinan', 'cat' => 'Karakter & Disiplin'],
                            ['label' => 'Olahraga & Futsal', 'cat' => 'Kebugaran & Sport'],
                            ['label' => 'Seni Musik & Vokal', 'cat' => 'Kreativitas & Seni'],
                            ['label' => 'PMR & Kemanusiaan', 'cat' => 'Sosial & Kesehatan'],
                            ['label' => 'English Club', 'cat' => 'Bahasa & Wawasan'],
                            ['label' => 'Tahfidz & Keislaman', 'cat' => 'Religius & Akhlak'],
                            ['label' => 'Dokumentasi Acara', 'cat' => 'Momen Bersama'],
                        ];
                    @endphp

                    @foreach($galeriItems as $item)
                    <div class="glow-card p-4 rounded-2xl reveal group border border-slate-800 hover:border-cyan-400/40 transition-all flex flex-col justify-between h-36">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="text-[10px] text-cyan-400/80 font-mono">{{ $item['cat'] }}</span>
                            <h4 class="text-xs font-bold text-white group-hover:text-cyan-300 transition-colors mt-0.5 leading-snug">{{ $item['label'] }}</h4>
                            <p class="text-[10px] text-slate-500 mt-1">Dokumentasi menyusul</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 6. VISI & MISI SEKOLAH (Modern Glow Card) -->
        <!-- ============================================================== -->
        <section id="visi-misi" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <div class="text-center max-w-2xl mx-auto reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-2">
                        <span>Pilar Utama Sekolah</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                        Visi & <span class="text-cyan-400">Misi</span> Sekolah
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        Pedoman dan landasan utama SMP Science Mutiara Insani menyelenggarakan pendidikan berkualitas.
                    </p>
                </div>

                <!-- Kartu Visi (Luminous Highlight) -->
                <div class="glow-card p-6 sm:p-8 rounded-3xl reveal border-cyan-400/30 relative overflow-hidden group">
                    <div class="absolute -right-16 -top-16 w-56 h-56 bg-cyan-500/15 rounded-full blur-[80px]"></div>
                    <div class="relative flex flex-col md:flex-row items-start md:items-center gap-5">
                        <div class="w-13 h-13 rounded-2xl bg-cyan-500/20 border border-cyan-400/50 text-cyan-300 flex items-center justify-center shrink-0 shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded bg-cyan-950/70 border border-cyan-500/40 text-[10px] font-mono font-bold text-cyan-300 uppercase tracking-widest mb-2">
                                VISI SEKOLAH
                            </span>
                            <blockquote class="text-base sm:text-lg md:text-xl font-bold text-white font-heading leading-relaxed">
                                "Terwujudnya peserta didik yang memiliki jatidiri yang tangguh dan kompeten yang berlandaskan nilai-nilai science untuk masa depan gemilang."
                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- 7 Misi Sekolah (Grid Kompak) -->
                <div>
                    <h3 class="text-sm font-bold text-cyan-300 font-heading mb-4 reveal flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                        <span>7 Misi Utama Sekolah</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 stagger-items">
                        @php
                            $misiList = [
                                "Menciptakan profil pelajar yang berakhlak mulia dan rajin beribadah.",
                                "Menciptakan pembelajaran yang menarik, menyenangkan dan berkarakter yang mampu memfasilitasi pelajar sesuai bakat dan minatnya.",
                                "Mendidik dan menjadikan siswa memiliki nilai sikap dan ketrampilan dibidang science.",
                                "Menciptakan lingkungan sekolah yang bersih, asri dan ramah anak.",
                                "Memfasilitasi pelajar untuk dapat mengembangkan potensi dan kemampuannya secara optimal.",
                                "Mengembangkan sekolah berwawasan global yang didasari nilai-nilai budaya dan nilai-nilai luhur bangsa.",
                                "Melibatkan orang tua dan masyarakat dalam proses pembelajaran dan pengembangan sekolah."
                            ];
                        @endphp

                        @foreach($misiList as $index => $misi)
                        <div class="glow-card p-4 rounded-xl reveal flex items-start gap-3 border border-slate-800">
                            <span class="w-6 h-6 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 font-mono font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                0{{ $index + 1 }}
                            </span>
                            <p class="text-xs text-slate-300 leading-relaxed font-medium">
                                {{ $misi }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 7. PROFIL, VIDEO & LOKASI SEKOLAH (Ukuran Pas & Kompak) -->
        <!-- ============================================================== -->
        <section id="profil-lokasi" class="scroll-mt-20 py-10 sm:py-14 border-t border-cyan-500/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-8 reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold mb-2">
                        <span>Mengenal Lingkungan Kami</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                        Video Profil & <span class="text-cyan-400">Lokasi Sekolah</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        SMP Science Mutiara Insani — lingkungan pendidikan untuk belajar, berkolaborasi, dan mengembangkan potensi diri.
                    </p>
                </div>

                <!-- 2-Column Grid: Video Profil & Live Maps (Ukuran Proporsional & Rapi) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">
                    
                    <!-- Kartu Video Profil (Kompak, Height Terkontrol) -->
                    <div class="glow-card p-4 sm:p-5 rounded-2xl reveal-left flex flex-col justify-between">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-white">Video Profil Sekolah</h3>
                                    <p class="text-[11px] text-slate-400">SMP Science Mutiara Insani</p>
                                </div>
                            </div>
                            <a href="https://youtu.be/NYeyeyumWPA?si=ncWARGMhPVnf24Wv" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-red-400 hover:text-red-300 flex items-center gap-1 transition-colors">
                                <span>Buka YouTube</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        <!-- Iframe Video (Kompak aspect ratio, tinggi terkontrol ~220px) -->
                        <div class="rounded-xl overflow-hidden border border-slate-700/60 shadow-inner bg-black/40 h-52 sm:h-56">
                            <iframe
                                src="https://www.youtube.com/embed/NYeyeyumWPA"
                                title="Video Profil SMP Science Mutiara Insani"
                                class="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <!-- Kartu Live Google Maps (Kompak, Height Terkontrol) -->
                    <div class="glow-card p-4 sm:p-5 rounded-2xl reveal-right flex flex-col justify-between">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-white">Temukan Lokasi Kami</h3>
                                    <p class="text-[11px] text-slate-400">Munjuljaya, Purwakarta</p>
                                </div>
                            </div>
                            <a href="https://www.google.com/maps/place/SMP+Science+Mutiara+Insani/@-6.5312535,107.4705822,17z/data=!3m1!4b1!4m6!3m5!1s0x2e690f71de365203:0x25ee54b1bd5ccdb1!8m2!3d-6.5312535!4d107.4731571!16s%2Fg%2F11s53ys3pp?entry=ttu&g_ep=EgoyMDI2MDkwMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition-colors">
                                <span>Buka Maps</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        <!-- Iframe Google Maps (Kompak, tinggi terkontrol ~220px) -->
                        <div class="rounded-xl overflow-hidden border border-slate-700/60 shadow-inner bg-black/40 h-52 sm:h-56">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.7840131971714!2d107.4705822!3d-6.5312535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690f71de365203%3A0x25ee54b1bd5ccdb1!2sSMP%20Science%20Mutiara%20Insani!5e0!3m2!1sid!2sid!4v1710000000000!5m2!1sid!2sid"
                                class="w-full h-full border-0"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                </div>

                <!-- Info Sekolah Bar (Rapi & Kompak di Bawah Video/Peta) -->
                <div class="reveal mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="glow-card p-3 rounded-xl flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-cyan-500/10 text-cyan-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-300 truncate">Munjuljaya, Purwakarta, Jawa Barat 41117</span>
                    </div>

                    <div class="glow-card p-3 rounded-xl flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-teal-500/10 text-teal-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-300 truncate">Senin - Jum'at (Tutup 14.40 WIB)</span>
                    </div>

                    <div class="glow-card p-3 rounded-xl flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-purple-500/10 text-purple-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <a href="https://smpsciencemutiarainsani.com" target="_blank" class="text-[11px] text-cyan-300 hover:underline truncate">smpsciencemutiarainsani.com</a>
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 8. AJAKAN MASUK KE PORTAL (Call To Action Kompak) -->
        <!-- ============================================================== -->
        <section class="py-12 sm:py-16 border-t border-cyan-500/10 relative overflow-hidden">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
                <div class="glow-card p-8 sm:p-10 rounded-3xl border-cyan-500/40 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-cyan-500/20 rounded-full blur-[90px]"></div>
                    <div class="relative">
                        <span class="inline-block px-3 py-1 rounded-full bg-cyan-950/60 border border-cyan-500/30 text-xs font-mono font-bold text-cyan-300 uppercase tracking-wider mb-4">
                            MASUK KE PORTAL
                        </span>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white font-heading mb-3">
                            Saatnya Jelajahi Potensimu
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto mb-6 leading-relaxed">
                            Mulai perjalananmu dalam mengenali minat, menemukan bakat, dan mengembangkan potensi melalui kegiatan ekstrakurikuler bersama Eksentra.
                        </p>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 hover:brightness-110 text-slate-950 px-7 py-3.5 rounded-xl font-extrabold text-sm transition-all shadow-[0_0_25px_rgba(34,211,238,0.5)] hover:-translate-y-0.5 active:scale-95">
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            <span>Masuk ke Portal Eksentra</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ============================================================== -->
    <!-- 9. FOOTER: Identitas Eksentra & Copyright Dinamis -->
    <!-- ============================================================== -->
    <footer class="bg-[#05080f] text-slate-300 py-10 border-t border-slate-800 relative z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-slate-800/80">
                
                <!-- Kolom 1: Nama & Deskripsi Eksentra -->
                <div class="md:col-span-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Eksentra" class="w-9 h-9 object-contain">
                        <div>
                            <h4 class="font-extrabold text-base text-white font-heading">Eksentra</h4>
                            <p class="text-[10px] text-cyan-400 font-semibold">Portal Ekstrakurikuler SMP Science Mutiara Insani</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Satu portal untuk mendukung pengelolaan kegiatan ekstrakurikuler yang terintegrasi dan terstruktur.
                    </p>
                </div>

                <!-- Kolom 2: Info Lokasi -->
                <div class="md:col-span-4 space-y-2">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider font-mono">Alamat Sekolah</h5>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Munjuljaya, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41117
                    </p>
                    <p class="text-xs text-cyan-400/90 font-medium">
                        Jam Operasional: Senin - Jum'at (Tutup 14.40 WIB)
                    </p>
                </div>

                <!-- Kolom 3: Tautan Navigasi -->
                <div class="md:col-span-3 space-y-2">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider font-mono">Navigasi</h5>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li><a href="#hero" class="hover:text-cyan-300 transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-cyan-300 transition-colors">Tentang Eksentra</a></li>
                        <li><a href="#tujuan" class="hover:text-cyan-300 transition-colors">Tujuan Ekskul</a></li>
                        <li><a href="#keunggulan" class="hover:text-cyan-300 transition-colors">Keunggulan</a></li>
                        <li><a href="#galeri" class="hover:text-cyan-300 transition-colors">Galeri</a></li>
                        <li><a href="#visi-misi" class="hover:text-cyan-300 transition-colors">Visi & Misi</a></li>
                        <li><a href="#profil-lokasi" class="hover:text-cyan-300 transition-colors">Video & Lokasi</a></li>
                        <li><a href="{{ route('login') }}" class="text-cyan-400 font-bold hover:text-cyan-300 transition-colors">Masuk ke Portal →</a></li>
                    </ul>
                </div>

            </div>

            <!-- Copyright Dinamis -->
            <div class="pt-6 text-center text-xs text-slate-500">
                <p>© {{ date('Y') }} SMP Science Mutiara Insani. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- ============================================================== -->
    <!-- INTERAKTIF JAVASCRIPT: Background Particles, ScrollSpy, Reveal -->
    <!-- ============================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Interactive Glow Particles Canvas (Kaya ada yang gerak-gerak glow in the dark)
            const canvas = document.getElementById('glow-canvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let width = canvas.width = window.innerWidth;
                let height = canvas.height = window.innerHeight;

                window.addEventListener('resize', () => {
                    width = canvas.width = window.innerWidth;
                    height = canvas.height = window.innerHeight;
                });

                // Buat partikel bercahaya
                const particleCount = Math.min(Math.floor(window.innerWidth / 20), 45);
                const particles = [];

                for (let i = 0; i < particleCount; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        radius: Math.random() * 2 + 1,
                        vx: (Math.random() - 0.5) * 0.45,
                        vy: (Math.random() - 0.5) * 0.45,
                        alpha: Math.random() * 0.5 + 0.2,
                        color: Math.random() > 0.4 ? 'rgba(34, 211, 238, ' : 'rgba(45, 212, 191, '
                    });
                }

                function animateParticles() {
                    ctx.clearRect(0, 0, width, height);

                    // Gambar garis tipis penghubung partikel yang berdekatan
                    for (let i = 0; i < particles.length; i++) {
                        for (let j = i + 1; j < particles.length; j++) {
                            const dx = particles[i].x - particles[j].x;
                            const dy = particles[i].y - particles[j].y;
                            const dist = Math.sqrt(dx * dx + dy * dy);

                            if (dist < 120) {
                                ctx.strokeStyle = `rgba(34, 211, 238, ${0.12 * (1 - dist / 120)})`;
                                ctx.lineWidth = 0.6;
                                ctx.beginPath();
                                ctx.moveTo(particles[i].x, particles[i].y);
                                ctx.lineTo(particles[j].x, particles[j].y);
                                ctx.stroke();
                            }
                        }
                    }

                    // Gambar setiap partikel bercahaya
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
                        ctx.shadowBlur = 8;
                        ctx.shadowColor = 'rgba(34, 211, 238, 0.6)';
                        ctx.fill();
                        ctx.shadowBlur = 0;
                    });

                    requestAnimationFrame(animateParticles);
                }

                animateParticles();
            }

            // 2. Intersection Observer untuk Reveal Animation
            const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            revealElements.forEach(el => observer.observe(el));

            // 3. Navbar ScrollSpy: Sesuai Section yang Aktif
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            const nav = document.getElementById('main-nav');

            const activeClass = ['text-cyan-300', 'bg-cyan-500/10'];
            const inactiveClass = ['text-slate-300'];

            // Hilangkan border & fokus saat diklik agar tidak memunculkan garis putih bawaan browser
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    this.blur();
                });
            });

            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function () {
                    this.blur();
                });
            });

            function updateScrollSpy() {
                const scrollPos = window.scrollY + 120;
                let currentId = 'hero';

                sections.forEach(sec => {
                    const top = sec.offsetTop;
                    const height = sec.offsetHeight;
                    if (scrollPos >= top && scrollPos < top + height) {
                        currentId = sec.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    const href = link.getAttribute('href').replace('#', '');
                    if (href === currentId) {
                        link.classList.remove(...inactiveClass, 'border', 'border-cyan-500/30');
                        link.classList.add(...activeClass);
                    } else {
                        link.classList.remove(...activeClass, 'border', 'border-cyan-500/30');
                        link.classList.add(...inactiveClass);
                    }
                });
            }

            window.addEventListener('scroll', updateScrollSpy, { passive: true });
            updateScrollSpy();
        });

        // 4. Mobile Menu Handlers
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.add('hidden');
        }
    </script>

</body>
</html>