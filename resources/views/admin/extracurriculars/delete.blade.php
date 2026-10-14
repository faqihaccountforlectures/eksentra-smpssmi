<div id="deleteModal-{{ $ekskul->id }}" class="hidden fixed inset-0 z-[9999] bg-[#070b14]/80 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
    <div class="glow-card w-full max-w-md rounded-2xl border border-rose-500/30 overflow-hidden text-center relative p-6 sm:p-7 shadow-[0_0_50px_rgba(0,0,0,0.8),0_0_30px_rgba(244,63,94,0.18)] animate-[scale-in_0.2s_ease-out]">
        <!-- Ornamen Glow Merah -->
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="w-14 h-14 bg-rose-500/15 border border-rose-500/40 text-rose-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_0_20px_rgba(244,63,94,0.3)]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>

            <h3 class="font-extrabold text-lg sm:text-xl text-white font-heading tracking-wide mb-2">Hapus Ekstrakurikuler?</h3>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6 font-medium">
                Data ekstrakurikuler <span class="font-bold text-cyan-300">{{ $ekskul->name }}</span> akan dihapus secara permanen. Seluruh data pendaftar pada ekstrakurikuler ini juga akan terhapus dan tidak dapat dipulihkan kembali.
            </p>
            
            <form action="{{ route('extracurriculars.destroy', $ekskul->id) }}" method="POST" class="flex flex-col-reverse sm:flex-row gap-3 justify-center items-center">
                @csrf
                @method('DELETE')
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $ekskul->id }}').classList.add('hidden')" class="w-full sm:w-auto px-6 py-2.5 bg-[#070b14] hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-cyan-500/20 hover:border-cyan-400/40 transition-all active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-extrabold text-xs rounded-xl shadow-[0_0_20px_rgba(244,63,94,0.4)] hover:shadow-[0_0_30px_rgba(244,63,94,0.6)] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Ya, Hapus Data</span>
                </button>
            </form>
        </div>
    </div>
</div>
