<!-- Modal Reset Password Pembina -->
<div id="resetPasswordModal-{{ $coach->id }}" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl border border-slate-200 overflow-hidden text-center relative p-6 sm:p-7 shadow-xl animate-[scale-in_0.2s_ease-out]">
        <div class="relative z-10">
            <div class="w-14 h-14 bg-amber-50 border border-amber-200 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>

            <h3 class="font-bold text-lg sm:text-xl text-slate-900 font-heading tracking-wide mb-2">Reset Kata Sandi Pembina?</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4 font-medium">
                Kata sandi untuk akun pembina <span class="font-bold text-slate-900">{{ $coach->coachProfile->full_name ?? $coach->username }}</span> ({{ $coach->username }}) akan dikembalikan ke kata sandi default sistem:
            </p>

            <div class="mb-4 inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl font-mono text-sm font-bold shadow-inner">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>pembinasmpsmi</span>
            </div>

            <p class="text-xs text-slate-500 mb-6 italic">
                * Pembina akan diwajibkan untuk membuat kata sandi baru pribadi saat login kembali ke sistem.
            </p>
            
            <form action="{{ route('coaches.reset-password', $coach->id) }}" method="POST">
                @csrf
                
                <div style="display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: center !important; gap: 12px !important; width: 100% !important;">
                    <button type="button" onclick="document.getElementById('resetPasswordModal-{{ $coach->id }}').classList.add('hidden')" style="display: inline-flex !important; align-items: center !important; justify-content: center !important; flex: 0 0 auto !important;" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all active:scale-95 shadow-sm whitespace-nowrap">
                        Batal
                    </button>
                    <button type="submit" style="display: inline-flex !important; align-items: center !important; justify-content: center !important; flex: 0 0 auto !important;" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 active:scale-95 whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Ya, Reset Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
