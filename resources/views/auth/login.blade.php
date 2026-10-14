<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Portal Eksentra — SMP Science Mutiara Insani</title>
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

        .glow-login-card {
            background: linear-gradient(135deg, rgba(13, 21, 39, 0.9) 0%, rgba(8, 13, 24, 0.95) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(34, 211, 238, 0.25);
            box-shadow: 0 0 40px -5px rgba(6, 182, 212, 0.2), 0 20px 45px -15px rgba(0, 0, 0, 0.85);
        }

        .neon-shadow-cyan {
            filter: drop-shadow(0 0 16px rgba(6, 182, 212, 0.45));
        }
    </style>
</head>
<body class="bg-[#070b14] text-slate-100 min-h-screen flex flex-col justify-center items-center p-3 sm:p-5 md:p-6 selection:bg-cyan-500 selection:text-black relative overflow-x-hidden">

    <!-- CANVAS PARTICLES (Gerak Halus Glow in the Dark) -->
    <canvas id="glow-canvas"></canvas>

    <!-- FLOATING GLOW ORBS -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-20 -left-20 w-[420px] h-[420px] bg-cyan-500/15 rounded-full blur-[110px] animate-float"></div>
        <div class="absolute -bottom-20 -right-20 w-[460px] h-[460px] bg-teal-500/15 rounded-full blur-[130px] animate-float-delay"></div>
        <div class="absolute top-1/3 right-10 w-[300px] h-[300px] bg-purple-500/10 rounded-full blur-[100px] animate-float-slow"></div>
    </div>

    <!-- WRAPPER UTAMA (Melebar ke Samping, Ringkas Vertikal) -->
    <div class="w-full max-w-4xl relative z-10 my-auto py-2">

        <!-- Top Bar Navigasi Mini -->
        <div class="mb-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/80 hover:bg-slate-800/90 text-slate-300 hover:text-cyan-300 border border-slate-700/60 hover:border-cyan-400/40 text-xs font-semibold transition-all backdrop-blur-md shadow-sm group">
                <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Beranda Eksentra</span>
            </a>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-semibold text-cyan-400/80">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>SECURE ACCESS PORTAL</span>
            </span>
        </div>

        <!-- CONTAINER KARTU LOGIN MELEBAR HORIZONTAL (2 Kolom Seimbang) -->
        <div class="glow-login-card rounded-3xl overflow-hidden grid grid-cols-1 md:grid-cols-12 transition-all">
            
            <!-- ========================================================= -->
            <!-- KOLOM KIRI: Logo & Identitas Brand Saja (md:col-span-5) -->
            <!-- ========================================================= -->
            <div class="md:col-span-5 p-6 sm:p-8 bg-gradient-to-br from-slate-900/80 via-[#0d162b]/90 to-[#080e1b]/80 border-b md:border-b-0 md:border-r border-slate-800/80 flex flex-col justify-center items-center text-center relative overflow-hidden">
                <!-- Decorative Glow Blob -->
                <div class="absolute -right-10 -top-10 w-44 h-44 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col items-center">
                    <!-- Logo Sekolah dengan Pendar Cahaya -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mb-4 relative inline-flex items-center justify-center">
                        <div class="absolute -inset-1.5 rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-400 opacity-60 blur-md"></div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMP Science Mutiara Insani" class="relative w-full h-full object-contain drop-shadow-md">
                    </div>

                    <!-- Identitas Brand EKSENTRA -->
                    <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight mb-1">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 neon-shadow-cyan">
                            EKSENTRA
                        </span>
                    </h1>
                    <p class="text-xs sm:text-sm font-bold text-white mb-1">Portal Ekstrakurikuler</p>
                    <p class="text-slate-400 text-xs font-medium leading-relaxed max-w-[220px]">SMP Science Mutiara Insani Purwakarta</p>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- KOLOM KANAN: Form & Tab Navigasi Login (md:col-span-7) -->
            <!-- ========================================================= -->
            <div class="md:col-span-7 p-6 sm:p-7 flex flex-col justify-center bg-slate-950/60 relative">
                
                <!-- Pesan Error Session (Jika ada error login) -->
                @if(session('error'))
                    <div class="bg-red-950/60 text-red-200 p-3 rounded-xl text-xs font-semibold mb-4 border border-red-500/40 flex items-center gap-2.5 text-left shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                        <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- TAB NAVIGASI METODE LOGIN (PISAHKAN SISWA/PEMBINA DAN ADMIN) -->
                <div class="grid grid-cols-2 p-1 bg-slate-900/80 border border-slate-800 rounded-xl mb-4 text-xs font-bold">
                    <!-- Tombol Tab 1: Siswa & Pembina -->
                    <button type="button" id="tab-btn-user" onclick="switchLoginTab('user')" class="py-2 rounded-lg transition-all shadow-sm bg-gradient-to-r from-cyan-500/20 to-teal-500/20 text-cyan-300 border border-cyan-400/40 shadow-[0_0_12px_rgba(6,182,212,0.25)] font-bold">
                        🎓 Siswa & Pembina
                    </button>
                    <!-- Tombol Tab 2: Admin -->
                    <button type="button" id="tab-btn-admin" onclick="switchLoginTab('admin')" class="py-2 rounded-lg transition-all text-slate-400 font-medium hover:text-slate-200">
                        🔐 Administrator
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- JALUR 1: LOGIN SISWA & PEMBINA (MENGGUNAKAN GOOGLE SSO) -->
                <!-- ========================================================= -->
                <div id="panel-user" class="space-y-3.5">
                    <div class="p-3.5 rounded-xl bg-cyan-950/40 border border-cyan-500/30 text-left">
                        <p class="text-xs font-bold text-cyan-300 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Akses Siswa & Pembina
                        </p>
                        <p class="text-[11px] text-slate-300 leading-relaxed font-medium">
                            Seluruh siswa dan pembina masuk menggunakan <strong class="text-cyan-300">Akun Google belajar resmi</strong> (<code class="bg-cyan-950/80 px-1 py-0.5 rounded text-cyan-200 border border-cyan-500/30 font-semibold font-mono">@belajar.id</code> atau email resmi terdaftar).
                        </p>
                    </div>

                    <!-- Tombol Login Google Resmi (Glow Accent) -->
                    <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-3 bg-slate-900/90 hover:bg-slate-800 text-white border border-slate-700/80 hover:border-cyan-400/60 rounded-xl p-3 text-xs sm:text-sm font-bold shadow-md hover:shadow-[0_0_20px_rgba(34,211,238,0.35)] active:scale-95 transition-all duration-200 group">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>
                </div>

                <!-- ========================================================= -->
                <!-- JALUR 2: LOGIN ADMINISTRATOR (EMAIL & PASSWORD) -->
                <!-- ========================================================= -->
                <div id="panel-admin" class="hidden space-y-3.5">
                    <div class="p-3 rounded-xl bg-slate-900/70 border border-slate-800 text-left">
                        <p class="text-xs font-bold text-slate-200 mb-0.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Akses Staf Administrator
                        </p>
                        <p class="text-[11px] text-slate-400 leading-relaxed font-medium">
                            Masukkan email resmi admin dan password Anda untuk mengelola portal.
                        </p>
                    </div>

                    <!-- Form Login Manual Administrator -->
                    <form method="POST" action="{{ route('login.post') }}" class="text-left space-y-3">
                        @csrf
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-300 mb-1">Email Administrator</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/30 text-xs font-medium transition-all" placeholder="admin@smpsmi.com" required>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-300 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="admin_password" class="w-full px-3.5 py-2 pr-10 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/30 text-xs font-medium transition-all" placeholder="••••••••" required>
                                <button type="button" onclick="toggleAdminPassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-cyan-300 focus:outline-none transition-colors" title="Lihat Password">
                                    <svg id="eye-icon-admin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 hover:brightness-110 text-slate-950 font-extrabold py-2.5 rounded-xl transition-all duration-200 shadow-[0_0_20px_rgba(34,211,238,0.45)] active:scale-95 text-xs">
                            Masuk Administrator
                        </button>
                    </form>
                </div>

                <!-- Footer Keterangan Aman -->
                <div class="pt-3 mt-3 border-t border-slate-800/80 flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-cyan-400/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Enkripsi Akses Resmi SMP Science Mutiara Insani</span>
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT JAVASCRIPT: Partikel Canvas, Tab Switch & Password Toggle -->
    <script>
        // 1. Interactive Ambient Particles Background
        const canvas = document.getElementById('glow-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            const particleCount = Math.min(Math.floor(window.innerWidth / 25), 35);
            const particles = [];

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2 + 1,
                    vx: (Math.random() - 0.5) * 0.4,
                    vy: (Math.random() - 0.5) * 0.4,
                    alpha: Math.random() * 0.5 + 0.2,
                    color: Math.random() > 0.4 ? 'rgba(34, 211, 238, ' : 'rgba(45, 212, 191, '
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
                            ctx.strokeStyle = `rgba(34, 211, 238, ${0.1 * (1 - dist / 110)})`;
                            ctx.lineWidth = 0.5;
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
                    ctx.shadowBlur = 8;
                    ctx.shadowColor = 'rgba(34, 211, 238, 0.6)';
                    ctx.fill();
                    ctx.shadowBlur = 0;
                });

                requestAnimationFrame(animateParticles);
            }

            animateParticles();
        }

        // 2. Tab Switch Handler (Siswa & Pembina vs Admin)
        function switchLoginTab(type) {
            const btnUser = document.getElementById('tab-btn-user');
            const btnAdmin = document.getElementById('tab-btn-admin');
            const panelUser = document.getElementById('panel-user');
            const panelAdmin = document.getElementById('panel-admin');

            const activeClass = "py-2 rounded-lg transition-all shadow-sm bg-gradient-to-r from-cyan-500/20 to-teal-500/20 text-cyan-300 border border-cyan-400/40 shadow-[0_0_12px_rgba(6,182,212,0.25)] font-bold";
            const inactiveClass = "py-2 rounded-lg transition-all text-slate-400 font-medium hover:text-slate-200 border border-transparent";

            if (type === 'admin') {
                btnAdmin.className = activeClass;
                btnUser.className = inactiveClass;
                panelAdmin.classList.remove('hidden');
                panelUser.classList.add('hidden');
            } else {
                btnUser.className = activeClass;
                btnAdmin.className = inactiveClass;
                panelUser.classList.remove('hidden');
                panelAdmin.classList.add('hidden');
            }
        }

        // 3. Password Toggle Handler
        function toggleAdminPassword() {
            const input = document.getElementById('admin_password');
            const icon = document.getElementById('eye-icon-admin');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.038 10.038 0 014.122-.963c4.478 0 8.268 2.943 9.543 7-1.025 1.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Buka tab admin otomatis jika ada error login manual
        @if(session('error') || $errors->any())
            switchLoginTab('admin');
        @endif
    </script>

</body>
</html>