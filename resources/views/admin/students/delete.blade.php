<!-- Modal Hapus Data Siswa -->
<div id="deleteModal-{{ $student->id }}" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl border border-slate-200 overflow-hidden text-center relative p-6 sm:p-7 shadow-xl animate-[scale-in_0.2s_ease-out]">
        <div class="relative z-10">
            <div class="w-14 h-14 bg-rose-50 border border-rose-200 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>

            <h3 class="font-bold text-lg sm:text-xl text-slate-900 font-heading tracking-wide mb-2">Hapus Data Siswa?</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6 font-medium">
                Akun siswa milik <span class="font-bold text-slate-900">{{ $student->studentProfile->full_name ?? $student->email }}</span> akan dihapus secara permanen beserta seluruh data pendaftaran dan riwayatnya. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="flex flex-col-reverse sm:flex-row gap-3 justify-center items-center">
                @csrf
                @method('DELETE')
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $student->id }}').classList.add('hidden')" class="w-full sm:w-auto px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Ya, Hapus Data</span>
                </button>
            </form>
        </div>
    </div>
</div>
