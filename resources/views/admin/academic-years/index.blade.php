@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('header', 'Tahun Ajaran')

@section('content')
    <!-- Notifikasi Alert Sukses / Gagal -->
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-emerald-500/10">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-rose-500/10">
        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: Panel Formulir Tambah Data -->
        <div class="xl:col-span-1">
            <div class="bg-white p-6 sm:p-7 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors duration-200">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="bg-blue-50 p-2.5 rounded-xl text-blue-800 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 font-heading tracking-wide">Registrasi Tahun Ajaran</h2>
                </div>
                
                <!-- FUNGSI KODE: Form mengarah ke method STORE di AcademicYearController -->
                <form action="{{ route('academic-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Nama Tahun Ajaran</label>
                        <input type="text" name="year_name" placeholder="Contoh: 2026/2027" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Aktivasi</label>
                        <div class="relative">
                            <select name="is_active" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold text-xs rounded-xl pl-3.5 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all appearance-none cursor-pointer">
                                <option value="1">Aktifkan Secara Global</option>
                                <option value="0">Tandai Tidak Aktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <!-- Informasi Bantuan Tambahan -->
                        <div class="mt-3 flex items-start gap-2 text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[11px] leading-relaxed">Pengaktifan data ini akan membatalkan status aktif pada entri Tahun Ajaran lainnya secara otomatis.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs py-3 rounded-xl transition-colors duration-200 flex justify-center items-center gap-2 mt-2 shadow-sm active:scale-95">
                        <span>Simpan Konfigurasi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Panel Tabel Daftar Data -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col h-full hover:border-blue-300 transition-colors duration-200">
                
                <!-- Header Panel Tabel -->
                <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-xl text-navy-base shadow-sm border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Tahun Ajaran</h2>
                    </div>
                    <!-- Menampilkan Total Data Saat Ini -->
                    <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3 py-1.5 rounded-lg border border-navy-light/40">Total: {{ $academicYears->total() }} Data</span>
                </div>
                
                <!-- Pembungkus (Wrapper) Tabel -->
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                <th class="px-7 py-4 font-bold">Periode Tahun Ajaran</th>
                                <th class="px-7 py-4 font-bold text-center">Status</th>
                                <th class="px-7 py-4 font-bold text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white-off text-sm text-navy-base">
                            @forelse($academicYears as $year)
                            <tr class="hover:bg-white-off/50 transition-colors group">
                                <td class="px-7 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2.5 h-2.5 rounded-full {{ $year->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-gray-muted/40' }}"></div>
                                        <span class="font-bold text-navy-dark text-base">{{ $year->year_name }}</span>
                                    </div>
                                </td>
                                <td class="px-7 py-4 text-center">
                                    @if($year->is_active)
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/50 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            Status Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/50 text-gray-muted px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-muted/50"></span>
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-7 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <!-- Tombol Perbarui (Edit) -->
                                        <a href="{{ route('academic-years.edit', $year->id) }}" class="text-gray-muted hover:text-navy-base transition-all p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 flex items-center justify-center bg-white" title="Ubah Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <!-- Tombol Hapus (Delete) -->
                                        <button type="button" onclick="document.getElementById('deleteModal-{{ $year->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-500 transition-all p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-100 flex items-center justify-center bg-white" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    @include('admin.academic-years.delete', ['year' => $year])
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-7 py-12 text-center text-gray-muted">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-white-off p-4 rounded-full mb-3 text-gray-muted">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="font-bold text-navy-dark text-sm">Belum Ada Riwayat Tahun Ajaran</p>
                                        <p class="text-xs text-gray-muted mt-0.5">Silakan tambahkan data melalui form konfigurasi di samping.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                @if($academicYears->hasPages())
                <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
                    <span class="text-xs text-gray-muted font-medium">
                        Menampilkan <b class="text-navy-dark">{{ $academicYears->firstItem() }}</b> - <b class="text-navy-dark">{{ $academicYears->lastItem() }}</b> dari <b class="text-navy-dark">{{ $academicYears->total() }}</b> entri
                    </span>
                    <div class="pagination-wrapper">
                        {{ $academicYears->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
@endsection
