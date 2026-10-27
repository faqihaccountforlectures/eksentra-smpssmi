<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Portal Eksentra — SMPS Science Mutiara Insani</title>
    <!-- Vite Assets (CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tailwind CSS CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        #glow-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .light-login-card {
            background: #ffffff;
            border: 1px solid rgba(30, 58, 138, 0.12);
            box-shadow: 0 20px 45px -10px rgba(30, 58, 138, 0.12), 0 10px 20px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col justify-center items-center p-3 sm:p-5 md:p-6 relative overflow-x-hidden">

    <!-- CANVAS PARTICLES (Gerak Halus Biru Tua) -->
    <canvas id="glow-canvas" class="opacity-35"></canvas>

    <!-- FLOATING SOFT BLUE ORBS -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-20 -left-20 w-[420px] h-[420px] bg-blue-200/40 rounded-full blur-[110px] animate-float"></div>
        <div class="absolute -bottom-20 -right-20 w-[460px] h-[460px] bg-indigo-200/35 rounded-full blur-[130px] animate-float-delay"></div>
        <div class="absolute top-1/3 right-10 w-[300px] h-[300px] bg-sky-200/30 rounded-full blur-[100px] animate-float-slow"></div>
    </div>

    <!-- WRAPPER UTAMA -->
    <div class="w-full max-w-4xl relative z-10 my-auto py-2">

        <!-- Top Bar Navigasi Mini -->
        <div class="mb-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-900 border border-slate-200 hover:border-blue-300 text-xs font-semibold transition-all shadow-sm group">
                <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Beranda Eksentra</span>
            </a>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-semibold text-blue-900">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>SECURE ACCESS PORTAL</span>
            </span>
        </div>

        <!-- CONTAINER KARTU LOGIN MELEBAR HORIZONTAL (2 Kolom Seimbang) -->
        <div class="light-login-card rounded-3xl overflow-hidden grid grid-cols-1 md:grid-cols-12 transition-all">
            
            <!-- KOLOM KIRI: Logo & Identitas Brand -->
            <div class="md:col-span-5 p-6 sm:p-8 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-950 text-white border-b md:border-b-0 md:border-r border-blue-900/20 flex flex-col justify-center items-center text-center relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-44 h-44 bg-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mb-4 relative inline-flex items-center justify-center p-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMPS Science Mutiara Insani" class="relative w-full h-full object-contain">
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight mb-1 text-white">
                        EKSENTRA
                    </h1>
                    <p class="text-xs sm:text-sm font-bold text-blue-200 mb-1">Portal Ekstrakurikuler</p>
                    <p class="text-blue-100/80 text-xs font-medium leading-relaxed max-w-[220px]">SMPS Science Mutiara Insani Purwakarta</p>
                </div>
            </div>

            <!-- KOLOM KANAN: Form & Tab Navigasi Login -->
            <div class="md:col-span-7 p-6 sm:p-7 flex flex-col justify-center bg-white relative">
                
                <!-- Pesan Error Session -->
                @if(session('error'))
                    <div class="bg-red-50 text-red-700 p-3 rounded-xl text-xs font-semibold mb-4 border border-red-200 flex items-center gap-2.5 text-left shadow-sm">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- TAB NAVIGASI PERAN LOGIN -->
                @php
                    $activeTab = session('active_tab', old('login_type', 'siswa'));
                    if (!in_array($activeTab, ['siswa', 'coach', 'admin'])) {
                        $activeTab = 'siswa';
                    }
                @endphp
                <div class="grid grid-cols-3 p-1 bg-slate-100 border border-slate-200 rounded-xl mb-4 text-xs font-bold gap-1">
                    <button type="button" id="tab-btn-siswa" onclick="switchLoginTab('siswa')" 
                        class="py-2 rounded-lg transition-colors duration-150 text-center {{ $activeTab === 'siswa' ? 'shadow-sm bg-blue-800 text-white font-bold' : 'text-slate-600 font-medium hover:text-blue-900 border border-transparent' }}">
                        🎓 Siswa
                    </button>
                    <button type="button" id="tab-btn-coach" onclick="switchLoginTab('coach')" 
                        class="py-2 rounded-lg transition-colors duration-150 text-center {{ $activeTab === 'coach' ? 'shadow-sm bg-blue-800 text-white font-bold' : 'text-slate-600 font-medium hover:text-blue-900 border border-transparent' }}">
                        🏅 Pembina
                    </button>
                    <button type="button" id="tab-btn-admin" onclick="switchLoginTab('admin')" 
                        class="py-2 rounded-lg transition-colors duration-150 text-center {{ $activeTab === 'admin' ? 'shadow-sm bg-blue-800 text-white font-bold' : 'text-slate-600 font-medium hover:text-blue-900 border border-transparent' }}">
                        🔐 Admin
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- TAB 1: LOGIN SISWA (NIS & PASSWORD) -->
                <!-- ========================================================= -->
                <div id="panel-siswa" class="space-y-3.5 {{ $activeTab === 'siswa' ? '' : 'hidden' }}">
                    <div class="p-3 rounded-xl bg-blue-50/80 border border-blue-200 text-left">
                        <p class="text-xs font-bold text-blue-900 mb-0.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Akses Peserta Didik
                        </p>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                            Masuk menggunakan <strong class="text-blue-900">Nomor Induk Siswa (NIS)</strong> dan kata sandi Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="text-left space-y-3">
                        @csrf
                        <input type="hidden" name="login_type" value="siswa">

                        <div>
                            <label for="siswa_nis" class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Siswa (NIS)</label>
                            <input type="text" name="nis" id="siswa_nis" value="{{ old('nis') }}" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium font-mono transition-all" placeholder="Masukkan NIS siswa" required>
                        </div>
                        
                        <div>
                            <label for="siswa_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                            <div class="relative">
                                <input type="password" name="password" id="siswa_password" class="w-full px-3.5 py-2 pr-10 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="••••••••" required>
                                <button type="button" onclick="togglePasswordVisibility('siswa_password', 'eye-icon-siswa')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-800 focus:outline-none transition-colors" title="Lihat Password">
                                    <svg id="eye-icon-siswa" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">Kata sandi awal: Tanggal lahir (format <code class="bg-blue-50 text-blue-900 px-1 py-0.5 rounded font-mono font-bold">YYYYMMDD</code>, misal: 20100817)</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-extrabold py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-blue-900/20 active:scale-95 text-xs">
                            Masuk sebagai Siswa
                        </button>
                    </form>
                </div>

                <!-- ========================================================= -->
                <!-- TAB 2: LOGIN PEMBINA (USERNAME / EMAIL & PASSWORD) -->
                <!-- ========================================================= -->
                <div id="panel-coach" class="space-y-3.5 {{ $activeTab === 'coach' ? '' : 'hidden' }}">
                    <div class="p-3 rounded-xl bg-indigo-50/80 border border-indigo-200 text-left">
                        <p class="text-xs font-bold text-indigo-900 mb-0.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Akses Pembina Ekstrakurikuler
                        </p>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                            Masuk menggunakan <strong class="text-indigo-900">Username</strong> dan kata sandi Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="text-left space-y-3">
                        @csrf
                        <input type="hidden" name="login_type" value="coach">

                        <div>
                            <label for="coach_username" class="block text-xs font-bold text-slate-700 mb-1">Username Pembina</label>
                            <input type="text" name="username" id="coach_username" value="{{ old('username') }}" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="Contoh: ahmadfaqih" required>
                        </div>
                        
                        <div>
                            <label for="coach_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                            <div class="relative">
                                <input type="password" name="password" id="coach_password" class="w-full px-3.5 py-2 pr-10 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="••••••••" required>
                                <button type="button" onclick="togglePasswordVisibility('coach_password', 'eye-icon-coach')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-800 focus:outline-none transition-colors" title="Lihat Password">
                                    <svg id="eye-icon-coach" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">Kata sandi awal: Tanggal lahir (format <code class="bg-indigo-50 text-indigo-900 px-1 py-0.5 rounded font-mono font-bold">YYYYMMDD</code>, misal: 19850512)</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-extrabold py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-blue-900/20 active:scale-95 text-xs">
                            Masuk sebagai Pembina
                        </button>
                    </form>
                </div>

                <!-- ========================================================= -->
                <!-- TAB 3: LOGIN ADMINISTRATOR (USERNAME & PASSWORD) -->
                <!-- ========================================================= -->
                <div id="panel-admin" class="space-y-3.5 {{ $activeTab === 'admin' ? '' : 'hidden' }}">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-left">
                        <p class="text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Akses Staf Administrator
                        </p>
                        <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                            Masukkan username admin dan password Anda untuk mengelola portal.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="text-left space-y-3">
                        @csrf
                        <input type="hidden" name="login_type" value="admin">

                        <div>
                            <label for="admin_username" class="block text-xs font-bold text-slate-700 mb-1">Username Administrator</label>
                            <input type="text" name="username" id="admin_username" value="{{ old('username') }}" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="admin" required>
                        </div>
                        
                        <div>
                            <label for="admin_password" class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="admin_password" class="w-full px-3.5 py-2 pr-10 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="••••••••" required>
                                <button type="button" onclick="togglePasswordVisibility('admin_password', 'eye-icon-admin')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-800 focus:outline-none transition-colors" title="Lihat Password">
                                    <svg id="eye-icon-admin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-extrabold py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-blue-900/20 active:scale-95 text-xs">
                            Masuk Administrator
                        </button>
                    </form>
                </div>

                <!-- Footer Keterangan Aman -->
                <div class="pt-3 mt-3 border-t border-slate-200 flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Enkripsi Akses Resmi SMPS Science Mutiara Insani</span>
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT JAVASCRIPT: Partikel Canvas, Tab Switch & Password Toggle -->
    <script>
        // 1. Ambient Particles Background
        const canvas = document.getElementById('glow-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            const particleCount = Math.min(Math.floor(window.innerWidth / 30), 28);
            const particles = [];

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2 + 1,
                    vx: (Math.random() - 0.5) * 0.3,
                    vy: (Math.random() - 0.5) * 0.3,
                    alpha: Math.random() * 0.3 + 0.1,
                    color: Math.random() > 0.5 ? 'rgba(30, 64, 175, ' : 'rgba(37, 99, 235, '
                });
            }

            function animateParticles() {
                ctx.clearRect(0, 0, width, height);

                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 110) {
                            ctx.strokeStyle = `rgba(30, 64, 175, ${0.08 * (1 - dist / 110)})`;
                            ctx.lineWidth = 0.6;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }

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

                requestAnimationFrame(animateParticles);
            }

            animateParticles();
        }

        // 2. Tab Switch Handler (Siswa, Coach, Admin)
        function switchLoginTab(type) {
            const tabs = ['siswa', 'coach', 'admin'];
            const activeClass = "py-2 rounded-lg transition-colors duration-150 shadow-sm bg-blue-800 text-white font-bold text-center";
            const inactiveClass = "py-2 rounded-lg transition-colors duration-150 text-slate-600 font-medium hover:text-blue-900 border border-transparent text-center";

            tabs.forEach(t => {
                const btn = document.getElementById('tab-btn-' + t);
                const panel = document.getElementById('panel-' + t);
                if (t === type) {
                    if (btn) btn.className = activeClass;
                    if (panel) panel.classList.remove('hidden');
                } else {
                    if (btn) btn.className = inactiveClass;
                    if (panel) panel.classList.add('hidden');
                }
            });
        }

        // 3. Password Toggle Handler Universal
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.038 10.038 0 014.122-.963c4.478 0 8.268 2.943 9.543 7-1.025 1.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>

</body>
</html>