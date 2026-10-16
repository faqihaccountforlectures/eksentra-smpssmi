<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi — Portal Eksentra SMPS Science Mutiara Insani</title>
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
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 relative overflow-x-hidden">

    <!-- FLOATING SOFT BLUE ORBS BACKGROUND -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-20 -left-20 w-[420px] h-[420px] bg-blue-200/40 rounded-full blur-[110px]"></div>
        <div class="absolute -bottom-20 -right-20 w-[460px] h-[460px] bg-indigo-200/35 rounded-full blur-[130px]"></div>
    </div>

    <!-- MAIN CONTAINER CARD -->
    <div class="w-full max-w-lg relative z-10 my-auto">
        
        <div class="bg-white rounded-3xl shadow-xl border border-blue-900/10 overflow-hidden">
            
            <!-- HEADER CARD (Gradient Navy) -->
            <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-950 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-36 h-36 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative flex flex-col items-center">
                    <div class="w-14 h-14 mb-3 inline-flex items-center justify-center p-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-md">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-300/30 text-amber-300 text-[11px] font-bold tracking-wider uppercase mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Pembaruan Keamanan Wajib
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black font-heading tracking-tight text-white mb-1">
                        Buat Kata Sandi Baru
                    </h1>
                    <p class="text-xs text-blue-200/90 font-medium max-w-xs">
                        Demi menjaga keamanan dan privasi akun Anda di Portal Eksentra.
                    </p>
                </div>
            </div>

            <!-- BODY CONTENT -->
            <div class="p-6 sm:p-8 space-y-5">
                
                <!-- Info Pengguna yang Login -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-800 text-white font-bold flex items-center justify-center font-heading shrink-0 shadow-sm">
                            {{ $user->initial }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 leading-tight">{{ $user->display_name }}</p>
                            <p class="text-[11px] text-slate-500 font-mono mt-0.5">
                                @if($user->role === 'siswa')
                                    NIS: <strong class="text-blue-900">{{ $user->studentProfile->nis ?? '-' }}</strong>
                                @else
                                    Email: <strong class="text-blue-900">{{ $user->email }}</strong>
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $user->role === 'siswa' ? 'bg-blue-100 text-blue-800' : 'bg-indigo-100 text-indigo-800' }}">
                        {{ $user->role === 'siswa' ? '🎓 Siswa' : '🏅 Pembina' }}
                    </span>
                </div>

                <!-- Alert Penjelasan -->
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[11px] leading-relaxed font-medium">
                        Anda baru saja masuk menggunakan kata sandi bawaan sistem (<code class="bg-amber-100 px-1 py-0.5 rounded font-mono font-bold">{{ $user->role === 'siswa' ? 'siswasmpsmi' : 'pembinasmpsmi' }}</code>). Anda wajib menggantinya dengan kata sandi baru sebelum dapat menggunakan portal.
                    </p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold space-y-1">
                        @foreach($errors->all() as $err)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $err }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Formulir Password Baru -->
                <form method="POST" action="{{ route('password.force_change.update') }}" class="space-y-4">
                    @csrf

                    <!-- Input Kata Sandi Baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required class="w-full px-3.5 py-2.5 pr-10 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="Minimal 6 karakter">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eye-new')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-800 focus:outline-none transition-colors" title="Lihat Kata Sandi">
                                <svg id="eye-new" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Input Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3.5 py-2.5 pr-10 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 text-xs font-medium transition-all" placeholder="Ulangi kata sandi baru">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-confirm')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-800 focus:outline-none transition-colors" title="Lihat Kata Sandi">
                                <svg id="eye-confirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-extrabold py-3 rounded-xl transition-all duration-200 shadow-md shadow-blue-900/20 active:scale-95 text-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Kata Sandi & Masuk ke Dashboard</span>
                    </button>
                </form>

                <!-- Tombol Keluar (Logout) -->
                <div class="pt-3 border-t border-slate-200 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition-colors inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Bukan akun Anda? Keluar (Logout)</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

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
