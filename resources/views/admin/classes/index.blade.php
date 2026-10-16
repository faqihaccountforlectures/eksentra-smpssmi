@extends('layouts.app')

@section('title', 'Data Kelas')
@section('header', 'Data Kelas')

@section('content')
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
        <div class="bg-emerald-100 p-2 rounded-lg text-emerald-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- FORM TAMBAH KELAS --}}
        <div class="xl:col-span-1">
            <div class="bg-white p-6 sm:p-7 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors duration-200">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="bg-blue-50 p-2.5 rounded-xl text-blue-800 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 font-heading tracking-wide">Tambah Kelas Baru</h2>
                </div>
                
                <form action="{{ route('classes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tingkat Kelas</label>
                        <div class="relative">
                            <select name="grade_level" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold text-sm rounded-xl pl-3 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 outline-none transition-colors duration-200 appearance-none">
                                <option value="" disabled selected>Pilih Tingkat...</option>
                                <option value="7">Kelas 7 (VII)</option>
                                <option value="8">Kelas 8 (VIII)</option>
                                <option value="9">Kelas 9 (IX)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Ruang/Kelas</label>
                        <input type="text" name="name" placeholder="Contoh: 7A, VIII B" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 outline-none transition-colors duration-200">
                    </div>

                    <button type="submit" class="w-full mt-2 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs py-3 rounded-xl shadow-sm active:scale-95 transition-colors duration-200 flex items-center justify-center gap-2">
                        <span>Simpan Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- DAFTAR KELAS --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col h-full hover:border-blue-300 transition-colors duration-200">
                <div class="px-7 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-xl text-blue-800 shadow-sm border border-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900 font-heading tracking-wide">Daftar Kelas</h2>
                    </div>
                    <span class="text-xs font-bold text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">Total: {{ $classes->total() }} Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-bold">
                                <th class="px-5 py-3.5 font-semibold border-b border-slate-100 text-center">Tingkat</th>
                                <th class="px-5 py-3.5 font-semibold border-b border-slate-100">Nama Kelas</th>
                                <th class="px-5 py-3.5 font-semibold border-b border-slate-100 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($classes as $classRoom)
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-bold text-xs">
                                        {{ $classRoom->grade_level }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">
                                    {{ $classRoom->name }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <a href="{{ route('classes.edit', $classRoom->id) }}" class="text-slate-600 hover:text-blue-800 transition-colors p-1.5 rounded-md hover:bg-blue-50 block" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <button onclick="document.getElementById('deleteModal-{{ $classRoom->id }}').classList.remove('hidden')" class="text-slate-600 hover:text-rose-600 transition-colors p-1.5 rounded-md hover:bg-rose-50" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('admin.classes.delete')
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="bg-slate-100 p-4 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-slate-700">Belum ada data kelas</p>
                                        <p class="text-xs mt-1 text-slate-500">Silakan tambahkan data kelas baru melalui form di samping.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- FUNGSI KODE: Paginasi (Pagination) --}}
                @if($classes->hasPages())
                <div class="px-7 py-4 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    {{ $classes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection





